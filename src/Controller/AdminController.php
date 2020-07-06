<?php

namespace App\Controller;

use App\Entity\QuizResult;
use App\Entity\User;
use App\Entity\Program;
use App\Form\SearchFilterType;
use App\Form\SelectProgramType;
use App\Repository\UserRepository;
use App\Entity\Video;
use App\Repository\ProgramRepository;
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

    private $userRepository;
    private $programRepository;

    public function __construct(userRepository $userRepository, programRepository $programRepository)
    {
        $this->userRepository = $userRepository;
        $this->programRepository = $programRepository;
    }
    /**
     *@param Request $request
     * @return Response
     * @Route("/", name="index")
     */
    public function index(Request $request):Response
    {
        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        $form = $this->createForm(SelectProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !empty($form->getData())) {
            $data = $form->getData();

            $users = $this->userRepository
                ->search($data['firstname'], $data['program']);
        }
        return $this->render('Admin/index.html.twig', ['page_name' => 'Candidats',
            'users' => $users, 'form' => $form->createView()]);
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
            ->findOneBy([]);

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
