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
        return $this->redirectToRoute('app_login');
    }

    /**
     * @Route("/inscription", name="home_inscription")
     */
    public function inscription()
    {
        return $this->redirectToRoute('app_register');
    }
}
