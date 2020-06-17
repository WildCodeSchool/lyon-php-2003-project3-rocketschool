<?php

namespace App\Controller;

use App\Entity\QuizResult;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Entity\Video;
use App\Form\VideoEditType;
use App\Services\GetVideo;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @IsGranted("ROLE_ADMIN")
 * Class AdminController
 * @package App\Controller
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
//Login is the home page
{
    /**
     * @Route("/", name="index")
     */
    public function index()
    {
        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();
        dump($users);
        $score = $this->getDoctrine()->getRepository(QuizResult::class)
            ->findAll();
        dump($score);
        return $this->render('Admin/index.html.twig', ['page_name' => 'Candidats',
            'users' => $users, 'score' => $score]);
    }

    /**
     * @param Request $request
     * @return Response
     * @Route("/video/edit", name="video_edit")
     */
    public function videoContentEdit(Request $request): Response
    {
        $video = $this->getDoctrine()
            ->getRepository(Video::class)
            ->find(1);

        $form = $this->createForm(VideoEditType::class, $video);
        $form->handleRequest($request);

        if ($video && $form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('ressources_index');
        }

        return $this->render('Admin/video.html.twig', [
                'page_name' => 'Vidéo - Édition',
                'video' => $video,
                'form' => $form->createView()
            ]);
    }
}
