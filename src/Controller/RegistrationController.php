<?php

namespace App\Controller;

use App\Entity\AccountsDuration;
use App\Entity\Checklist;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\AccountsDurationRepository;
use App\Security\LoginFormAuthenticator;
use App\Services\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

class RegistrationController extends AbstractController
{
    /**
     * @Route("/register", name="app_register")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param GuardAuthenticatorHandler $guardHandler
     * @param LoginFormAuthenticator $authenticator
     * @param AccountsDurationRepository $durationRepository
     * @param UserManager $userManager
     * @return Response
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        GuardAuthenticatorHandler $guardHandler,
        LoginFormAuthenticator $authenticator,
        AccountsDurationRepository $durationRepository,
        UserManager $userManager
    ): ?Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('profil');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $check = $_POST['verify_pass'];
            $pass = $form->get('plainPassword')->getData();

            if ($pass != $check) {
                return $this->render('registration/register.html.twig', [
                    'registrationForm' => $form->createView(),
                    'error_check_pass' => 'Erreur lors de la saisie du mot de passe'
                ]);
            }
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $duration = $durationRepository->findOneBy([]);
            $user->setAccountsDuration($duration);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            $accountsDuration = $durationRepository->findOneBy([]);
            if ($accountsDuration) {
                $accountsDuration->getDays();
            }

            $userManager->setDeletedAt($user);

            $entityManager->persist($user);
            $entityManager->flush();

            // do anything else you need here, like send an email

            return $guardHandler->authenticateUserAndHandleSuccess(
                $user,
                $request,
                $authenticator,
                'main' // firewall name in security.yaml
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'page_name' => 'Inscription'
        ]);
    }
}
