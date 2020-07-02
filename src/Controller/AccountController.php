<?php

namespace App\Controller;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="profil")
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function index(EntityManagerInterface $entityManager):Response
    {
        if ($_POST) {
            $title = self::cleanInput($_POST['title']);
            $content = self::cleanInput($_POST['content']);

            $note = new Note();
            $note->setTitle($title);
            $note->setContent($content);
            $note->setUser($this->getUser());
            $entityManager->persist($note);
            $entityManager->flush();
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController', 'page_name' => 'Mon Profil'
        ]);
    }

    public static function cleanInput(string $input):string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }
}
