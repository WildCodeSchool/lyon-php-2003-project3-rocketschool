<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RessourcesController extends AbstractController
{
    /**
     * @Route("/ressources", name="ressources")
     */
    public function index()
    {
        return $this->render('ressources/index.html.twig', [
            'controller_name' => 'RessourcesController', 'page_name' => 'Vidéos'
        ]);
    }

    /**
     * @Route("ressources/quizz", name="ressources_quizz")
     */
    public function quizz()
    {
        return $this->render('ressources/quizz.html.twig', ['page_name' => 'Inscription']);
    }

    /**
     * @Route("ressources/faq", name="ressources_faq")
     */

    public function faq()
    {
        return $this->render('ressources/faq.html.twig', ['page_name' => 'Faq']);
    }

    /**
     * @Route("ressources/guide", name="ressources_guide")
     */

    public function guideEntretien()
    {
        return $this->render('ressources/guide.html.twig', ['page_name' => 'Guide d\'entretien']);
    }
}
