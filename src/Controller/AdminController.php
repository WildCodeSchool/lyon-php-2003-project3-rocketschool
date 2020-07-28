<?php

namespace App\Controller;

use App\Entity\AccountsDuration;
use App\Entity\User;
use App\Entity\Program;
use App\Form\AccountsDurationType;
use App\Form\PdfType;
use App\Form\UserType;
use App\Form\SearchFilterType;
use App\Form\SelectProgramType;
use App\Repository\AccountsDurationRepository;
use App\Repository\PdfRepository;
use App\Repository\UserRepository;
use App\Entity\Video;
use App\Repository\ProgramRepository;
use App\Form\VideoEditType;
use App\Services\GetVideo;
use App\Services\Slugify;
use App\Services\UploadService;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
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
     * @param FormFactoryInterface $formFactory
     * @return Response
     * @Route("/", name="index")
     */
    public function index(
        Request $request,
        UserRepository $userRepo,
        AccountsDurationRepository $accDuRepo,
        FormFactoryInterface $formFactory
    ):Response {
        list($accountsDuration, $formAccDu) = self::editCandidateDuration($request, $accDuRepo, $userRepo);

        $formDelAts = [];

        $candidates = $userRepo->findCandidates();
        $candidate = null;
        foreach ($candidates as $candidate) {
            $form = $formFactory->createNamed('user_'.$candidate->getId(), UserType::class, $candidate);
            $formDelAts[] = $form;
        }

        $formsView = [];

        foreach ($formDelAts as $formDelAt) {
            $formDelAt->handleRequest($request);
            $formsView[] = $formDelAt->createView();

            if ($formDelAt->isSubmitted() && $formDelAt->isValid()) {
                $userId = $request->request->get('userId');
                $deletedAt = $formDelAt->get("deletedAt")->getData();

                $user = $userRepo->findOneBy(['id' => $userId]);
                if ($user && $deletedAt) {
                    $user->setDeletedAt($deletedAt);
                    $entityManager = $this->getDoctrine()->getManager();
                    $entityManager->persist($user);
                    $entityManager->flush();
                }
                return $this->redirectToRoute('admin_index');
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
            'users' => $candidates,
            'accountsDuration' => $accountsDuration,
            'form' => $form->createView(),
            'formAccDu' => $formAccDu->createView(),
            'formDelAts'=> $formsView
        ]);
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

    /**
     * @Route("/guide", name="guide")
     * @param Request $request
     * @param PdfRepository $pdfRepository
     * @param UploadService $uploadService
     * @return Response
     */
    public function guide(
        Request $request,
        PdfRepository $pdfRepository,
        UploadService $uploadService
    ): Response {
        $pdf = $pdfRepository->findOneBy([]);

        $form = $this->createForm(PdfType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $uploadedPdf = $form->get('path')->getData();

            if (!empty($uploadedPdf) && !empty($pdf)) {
                $newName = $uploadService->upload($uploadedPdf);
                $uploadedPdf->move(
                    $this->getParameter('pdf_uploads'),
                    $newName
                );
                $pdf->setPath(empty($newName)? "" : $newName);
                $entityManager->persist($pdf);
            }
            $entityManager->flush();

            $this->addFlash('success', 'Votre PDF à bien été enregistré');
            return $this->redirectToRoute('admin_guide');
        }

        return $this->render('Admin/guide.html.twig', [
            'page_name' => 'Guide entretien',
            'form' => $form->createView(),
        ]);
    }
}
