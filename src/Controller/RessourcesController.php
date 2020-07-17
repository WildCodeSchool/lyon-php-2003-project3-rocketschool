<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\Proposition;
use App\Entity\Question;
use App\Entity\Quizz;
use App\Entity\User;
use App\Repository\PropositionRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizzRepository;
use App\Entity\Faq;
use App\Entity\Video;
use App\Form\FaqSearchFieldType;
use App\Repository\FaqRepository;
use App\Repository\UserRepository;
use App\Services\QuizResultService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/ressources", name="ressources_")
 * @package App\Controller
 */
class RessourcesController extends AbstractController
{

    private $faqRepository;

    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $entityManager
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findOneBy([]);

        if ($_POST && $_POST['ready']) {
            $user = $userRepository->find($_POST['userId']);

            if (!empty($user)) {
                $checklist = $user->getChecklist();
                if ($checklist) {
                    $checklist->setCheckVideo(true);
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
            }
        }

        return $this->render('ressources/index.html.twig', [
            'controller_name' => 'RessourcesController',
            'page_name' => 'Vidéo',
            'video' => $video
        ]);
    }

    /**
     * @Route("/quizz", name="quizz")
     * @param QuizzRepository $quizzRepo
     * @param QuizResultService $quizResultService
     * @return string
     */
    public function quizz(QuizzRepository $quizzRepo, QuizResultService $quizResultService)
    {
        $quizz = $quizzRepo->findOneBy([]);
        $user = $this->getUser();
        $errors = null;
        $result = null;
        $postValide = true;

        if (!$quizResultService->isAllowed($user)) {
            return $this->redirectToRoute('ressources_index');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_POST['questions'])) {
                return $this->redirectToRoute('ressources_quizz');
            }
            list($postValide,$errors,$result) = self::quizzProcess($user, $quizResultService);
        }

        return $this->render('ressources/quizz.html.twig', [
            'page_name' => 'Quizz',
            'quizz' => $quizz,
            'errors' => $errors,
            'post' => $_POST,
            'result' => $result,
            'postValide' => $postValide,
        ]);
    }

    /**
     * @param Request $request
     * @param FaqRepository $faqRepository
     * @return Response
     * @Route("/faq", name="faq")
     */

    public function faq(Request $request, FaqRepository $faqRepository): Response
    {
        $this->faqRepository = $faqRepository;

        $faq = $this->getDoctrine()
            ->getRepository(Faq::class)
            ->findBy([], ['position' => 'ASC']);

        $form = $this->createForm(FaqSearchFieldType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !empty($form->getData())) {
            $data = $form->getData();
            $faq = $this->faqRepository
                ->findBySomeField($data['searchField'], $data['category']);
        }

        return $this->render('ressources/faq.html.twig', [
            'page_name' => 'FAQ',
            'form' => $form->createView(),
            'faq' => $faq
        ]);
    }

    public function quizzProcess($user, $quizResultService)
    {
        $postValide = true;
        $errors = null;
        $result = null;

        $manager = $this->getDoctrine()->getManager();
        $questionRepo = $manager->getRepository(Question::class);
        $propoRepo = $manager->getRepository(Proposition::class);
        $questions = $questionRepo->findAll();
        $nbrQuestionQuizz = count($questions);

        $errors = [];
        $nbrQuestionPost = count($_POST['questions']);

        if ($nbrQuestionQuizz !== $nbrQuestionPost) {
            $postValide = false;
        } else {
            foreach ($_POST["questions"] as $questionId => $propositions) {
                $question = $questionRepo->find($questionId);
                $goodAnswers = $propoRepo->findBy(['question' => $question, 'isGood' => true]);
                $goodAnswers = array_map(function ($prop) {
                    return $prop->getId();
                }, $goodAnswers);

                $errors[$questionId] = false;

                foreach ($propositions as $key => $value) {
                    if (!in_array($value, $goodAnswers)) {
                        $errors[$questionId] = true;
                    }
                }
                foreach ($goodAnswers as $key => $value) {
                    if (!in_array($value, $propositions)) {
                        $errors[$questionId] = true;
                    }
                }
            }
            $result = $quizResultService->calculate($errors, $nbrQuestionQuizz);
            $quizResultService->flush($user, $result);
        }

        return [$postValide,$errors,$result];
    }

    /**
     * @Route("/guide/{user}", name="guide")
     * @param User $user
     * @return Response
     */
    public function guide(User $user)
    {
        $checklist = $user->getChecklist();
        if ($checklist) {
            $checklist->setCheckGuide(true);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($checklist);
            $entityManager->flush();
        }

        return $this->redirect('http://www.google.fr');
    }
}
