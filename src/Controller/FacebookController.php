<?php

namespace App\Controller;

use App\Security\MyFacebookAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class FacebookController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/facebook", name="connect_facebook_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to Facebook!
        return $clientRegistry
            ->getClient('facebook_main') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'public_profile', 'email' // the scopes you want to access
            ], []);
    }

    /**
     * After going to Facebook, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/facebook/check", name="connect_facebook_check")
     */
    public function connectCheckAction(
        Request $request,
        ClientRegistry $clientRegistry,
        MyFacebookAuthenticator $fbAuthenticator,
        UserProviderInterface $userProvider
    ) {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

        ///** @var \KnpU\OAuth2ClientBundle\Client\Provider\FacebookClient $client */
        //$client = $clientRegistry->getClient('facebook_main');

        try {
            // the exact class depends on which provider you're using
            ///** @var \League\OAuth2\Client\Provider\FacebookUser $facebookAccount */
            //$facebookAccount = $client->fetchUser();

            // do something with all this new power!
            // e.g. $name = $user->getFirstName();
            $credencials = $fbAuthenticator->getCredentials($request);
            $fbAuthenticator->getUser($credencials, $userProvider);
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());
        }
    }
}
