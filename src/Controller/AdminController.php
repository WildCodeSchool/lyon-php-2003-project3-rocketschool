<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin", name="admin")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminController extends AbstractController
//Login is the home page
{
    /**
     * @Route("/", name="admin")
     */

    //request to display the table of candidates
    public function index()
    {
        $user = $this->getDoctrine()->getRepository(User::class)
            ->findAll();
        return $this->render('Admin/index.html.twig', ['page_name' => 'Candidats', 'user' => $user]);
    }
}
