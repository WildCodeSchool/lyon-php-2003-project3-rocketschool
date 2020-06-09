<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
//Login is the home page
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('Admin/index.html.twig', ['page_name' => 'Admin_Home']);
    }
}
