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
     * @return string
     */
    public function quizz(QuizzRepository $quizzRepo, PropositionRepository $propoRepo)
    {
        $quizz = $quizzRepo->findOneBy([]);
        $allProp = $propoRepo->findAll();
        $goodAnswers = [];
        $errors =[];
        dump($allProp);

//        //tableau des propositions : 'isGood' == true
//        foreach ($allProp as $propId => $info) {
//            $goodAnswers[$propId] =$info->getId();
//        }
//
//        dump($goodAnswers);



        if ($_SERVER['REQUEST_METHOD'] =='POST') {
            dump($_POST);
            foreach ($_POST["questions"] as $questionId => $propositions) {
                foreach ($propositions as $key => $value) {
                    if (!in_array($value, $goodAnswers)) {
                        $errors[$questionId][$key] = true;
                    } else {
                        $errors[$questionId][$key] = false;
                    }
                }
            }

            dump($errors);
            die();

            return $this->redirectToRoute('ressources_quizz', ['errors'=>$errors]);
        }


        return $this->render('ressources/quizz.html.twig', [
        'page_name' => 'Quizz',
        'quizz'=>$quizz,
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
            ->findAll();

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
