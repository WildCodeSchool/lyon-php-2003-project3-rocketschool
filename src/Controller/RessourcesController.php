<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Entity\Quizz;
use App\Repository\PropositionRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizzRepository;
use App\Entity\Faq;
use App\Entity\Video;
use App\Form\FaqSearchFieldType;
use App\Repository\FaqRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {

        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->findOneBy([]);

        if ($_GET) {
            if ($_GET['ready']) {
                $user = $this->getUser();
                $user->setIsReady(true);
                $entityManager->persist($user);
                $entityManager->flush();
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
     * @param PropositionRepository $propoRepo
     * @param QuestionRepository $questionRepo
     * @return string
     */
    public function quizz(
        QuizzRepository $quizzRepo,
        PropositionRepository $propoRepo,
        QuestionRepository $questionRepo
    ) {
        $quizz = $quizzRepo->findOneBy([]);
        $questions = $questionRepo->findAll();
        $nbrQuestionQuizz = count($questions);
        $errors = null;
        $postValide = true;

        if ($_SERVER['REQUEST_METHOD'] =='POST') {
            $errors = [];
            $nbrQuestionPost = count($_POST['questions']);

            if ($nbrQuestionQuizz !== $nbrQuestionPost) {
                $postValide  = false;
            }

            foreach ($_POST["questions"] as $questionId => $propositions) {
                $question = $questionRepo->find($questionId);
                $goodAnswers =$propoRepo->findBy(['question' => $question, 'isGood' => true]);
                $goodAnswers = array_map(function ($prop) {
                    return $prop->getId();
                }, $goodAnswers);

                $errors[$questionId] = false;

                foreach ($propositions as $key => $value) {
                    if (!in_array($value, $goodAnswers)) {
                        $errors[$questionId]= true;
                    }
                }
                foreach ($goodAnswers as $key => $value) {
                    if (!in_array($value, $propositions)) {
                        $errors[$questionId] = true;
                    }
                }
            }
        }

        return $this->render('ressources/quizz.html.twig', [

        'page_name' => 'Quizz',
        'quizz'=>$quizz,
        'errors'=> $errors,
        'post' => $_POST,
        'postValide' => $postValide
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

    /**
     * @Route("/guide", name="guide")
     */

    public function guide()
    {
        return $this->render('ressources/guide.html.twig', ['page_name' => 'Guide d\'entretien']);
    }
}
