<?php

namespace App\Security;

use App\Entity\Checklist;
use App\Entity\User; // your user entity
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Security\Authenticator\SocialAuthenticator;
use KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\LinkedInResourceOwner;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MyLinkedinAuthenticator extends SocialAuthenticator
{
    private $clientRegistry;
    private $entityManager;
    private $router;
    private $userRepository;
    private $passwordEncoder;

    public function __construct(
        ClientRegistry $clientRegistry,
        EntityManagerInterface $entityManager,
        RouterInterface $router,
        UserRepository $userRepository,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->clientRegistry = $clientRegistry;
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        // continue ONLY if the current ROUTE matches the check ROUTE
        return $request->attributes->get('_route') === 'connect_linkedin_check';
    }

    public function getCredentials(Request $request)
    {
        // this method is only called if supports() returns true

        // For Symfony lower than 3.4 the supports method need to be called manually here:
        // if (!$this->supports($request)) {
        //     return null;
        // }

        return $this->fetchAccessToken($this->getLinkedinClient());
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var LinkedInResourceOwner $linkedinUser */
        $linkedinUser = $this->getLinkedinClient()
            ->fetchUserFromToken($credentials);

        $email = $linkedinUser->getEmail();

        // 1) have they logged in with linkedin before? Easy!
        $existingUser = $this->entityManager->getRepository(User::class)
            ->findOneBy(['linkedinId' => $linkedinUser->getId()]);
        if ($existingUser) {
            return $existingUser;
        }

        // 2) do we have a matching user by email?
        $user = $this->userRepository
            ->findOneBy(['email' => $email]);

        // 3) Maybe you just want to "register" them by creating
        // a User object

        if (empty($user)) {
            $firstName = $linkedinUser->getFirstName();
            $lastName = $linkedinUser->getLastName();
            $checklist = new Checklist();

            $user = new User($checklist);
            $user->setLinkedinId($linkedinUser->getId())
                ->setEmail((empty($email)) ? "" : $email)
                ->setFirstname((empty($firstName)) ? "" : $firstName)
                ->setLastname((empty($lastName)) ? "" : $lastName);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $credentials->getToken()));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * @return LinkedInClient
     */
    private function getLinkedinClient()
    {
        return $this->clientRegistry
            // "linkedin" is the key used in config/packages/knpu_oauth2_client.yaml
            ->getClient('linkedin');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // change "home" to some route in your app
        $targetUrl = $this->router->generate('home');

        return new RedirectResponse($targetUrl);

        // or, on success, let the request continue to be handled by the controller
        //return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent.
     * This redirects to the 'login'.
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        return new RedirectResponse(
            '/connect/', // might be the site, where users choose their oauth provider
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
