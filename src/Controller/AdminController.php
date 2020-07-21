<?php

namespace App\Controller;

use App\Entity\AccountsDuration;
use App\Entity\QuizResult;
use App\Entity\User;
use App\Entity\Program;
use App\Form\AccountsDurationType;
use App\Form\UserType;
use App\Form\SearchFilterType;
use App\Form\SelectProgramType;
use App\Repository\AccountsDurationRepository;
use App\Repository\UserRepository;
use App\Entity\Video;
use App\Repository\ProgramRepository;
use App\Form\VideoEditType;
use App\Services\GetVideo;
use DateTime;
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
    private $accountsDuration;

    public function __construct(
        userRepository $userRepository,
        programRepository $programRepository,
        AccountsDurationRepository $accDuRepo
    ) {
        $this->userRepository = $userRepository;
        $this->programRepository = $programRepository;
        $this->accountsDuration = $accDuRepo;
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepo
     * @param AccountsDurationRepository $accDuRepo
     * @return Response
     * @Route("/", name="index")
     */
    public function index(Request $request, UserRepository $userRepo, AccountsDurationRepository $accDuRepo):Response
    {
        list($accountsDuration, $formAccDu) = self::editCandidateDuration($request, $accDuRepo, $userRepo);

        if ($_POST) {
            $deletedAt = $_POST['deletedAt'];
            $deletedAt = DateTime::createFromFormat('Y-m-d', $deletedAt);
            $userId = $_POST["userId"];
            $user = $userRepo->findOneBy(['id' => $userId]);
            if ($user && $deletedAt) {
                $entityManager = $this->getDoctrine()->getManager();
                $user->setDeletedAt($deletedAt);
                $entityManager->persist($user);
                $entityManager->flush();
            }
        }

        $users = $this->getDoctrine()->getRepository(User::class)
            ->findAll();

        $form = $this->createForm(SelectProgramType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && !empty($form->getData())) {
            $data = $form->getData();

            $users = $this->userRepository
                ->search($data['dataUsers'], $data['program']);
        }
        return $this->render('Admin/index.html.twig', ['page_name' => 'Candidats',
            'users' => $users,
            'accountsDuration' => $accountsDuration,
            'form' => $form->createView(),
            'formAccDu' => $formAccDu->createView()]);
    }

    /**
     * @param Request $request
     * @param AccountsDurationRepository $accDuRepo
     * @param UserRepository $userRepo
     * @return array
     * @Route("/account/duration/{user}", name="edit_account_duration")
     */
    public function editCandidateDuration(
        Request $request,
        AccountsDurationRepository $accDuRepo,
        UserRepository $userRepo
    ): array {
        $accountsDuration = $this->getDoctrine()->getRepository(AccountsDuration::class)
            ->findOneBy([]);

        $formAccDu = $this->createForm(AccountsDurationType::class, $accountsDuration);
        $formAccDu->handleRequest($request);

        if ($formAccDu->isSubmitted() && $formAccDu->isValid() && $accountsDuration) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accountsDuration);
            $entityManager->flush();
            $userRepo->updateCandidatesDuration($accDuRepo);
        }
        return [$accountsDuration, $formAccDu];
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
