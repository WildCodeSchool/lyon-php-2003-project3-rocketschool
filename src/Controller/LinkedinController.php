<?php

namespace App\Controller;

use App\Security\MyLinkedinAuthenticator;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class LinkedinController extends Controller
{
    /**
     * Link to this controller to start the "connect" process
     *
     * @Route("/connect/linkedin", name="connect_linkedin_start")
     */
    public function connectAction(ClientRegistry $clientRegistry)
    {
        // on Symfony 3.3 or lower, $clientRegistry = $this->get('knpu.oauth2.registry');

        // will redirect to linkedin!
        return $clientRegistry
            ->getClient('linkedin') // key used in config/packages/knpu_oauth2_client.yaml
            ->redirect([
                'r_liteprofile','r_emailaddress' // the scopes you want to access
            ], []);
    }

    /**
     * After going to linkedin, you're redirected back here
     * because this is the "redirect_route" you configured
     * in config/packages/knpu_oauth2_client.yaml
     *
     * @Route("/connect/linkedin/check", name="connect_linkedin_check")
     */
    public function connectCheckAction(
        Request $request,
        ClientRegistry $clientRegistry,
        UserProviderInterface $userProvider
    ) {
        // ** if you want to *authenticate* the user, then
        // leave this method blank and create a Guard authenticator
        // (read below)

       // /** @var \KnpU\OAuth2ClientBundle\Client\Provider\LinkedInClient $client */
        //$client = $clientRegistry->getClient('linkedin');

        try {
            // the exact class depends on which provider you're using
            //$linkedinAccount = $client->fetchUser();

            // do something with all this new power!
            //var_dump($linkedinAccount); die();
            // ...
        } catch (IdentityProviderException $e) {
            // something went wrong!
            // probably you should return the reason to the user
            var_dump($e->getMessage());
        }
    }
}
