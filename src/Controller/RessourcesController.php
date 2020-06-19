<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Entity\Quizz;
use App\Entity\Video;
use App\Repository\PropositionRepository;
use App\Repository\QuizzRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/ressources", name="ressources_")
 * @package App\Controller
 */
class RessourcesController extends AbstractController
{
    /**
     * @Route("/", name="index")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager)
    {

        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->find(1);

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
     * @param QuizzRepository $quizzRepository
     * @return Response
     */
    public function quizz(QuizzRepository $quizzRepository)
    {
        $quizz = $quizzRepository->findOneBy(['isEnable' => true]);


        return $this->render('ressources/quizz.html.twig', [
            'page_name' => 'Quizz',
            'quizz'=>$quizz,


        ]);
    }

    /**
     * @Route("/faq", name="faq")
     */

    public function faq()
    {
        return $this->render('ressources/faq.html.twig', ['page_name' => 'Faq']);
    }

    /**
     * @Route("/guide", name="guide")
     */

    public function guide()
    {
        return $this->render('ressources/guide.html.twig', ['page_name' => 'Guide d\'entretien']);
    }
}
