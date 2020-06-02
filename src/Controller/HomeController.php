<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController', 'page_name' => 'Connexion'
        ]);
    }
    /**
     * @Route("/inscription", name="home_inscription")
     */
    public function inscription()
    {
       return $this->render('home/inscription.html.twig', ['page_name' => 'Inscription']);
    }
}
