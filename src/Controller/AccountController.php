<?php

namespace App\Controller;

use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
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

        if ($_FILES) {
            list($uploaded, $failed) = self::uploadPhoto($_FILES);

            if (!empty($failed)) {
                return $this->render('account/index.html.twig', ['uploadError' => $failed]);
            }
            // MaJ BDD -> champs photo
            $user = $this->getUser();
            $user->setImage($uploaded);
            $entityManager->persist($user);
            $entityManager->flush();
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController', 'page_name' => 'Mon Profil'
        ]);
    }

    /**
     * @Route("/account/{note<\d+>}", name="edit_note")
     * @param Note $note
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function edit(Note $note, EntityManagerInterface $entityManager):Response
    {
        return $this->render('account/index.html.twig', ['note' => $note, 'page_name' => 'Mon Profil']);
    }

    public static function cleanInput(string $input):string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    public static function uploadPhoto($files):array
    {
        $files = $files['files'];

        $uploaded = '';
        $failed = '';

        define('ALLOWED_EXT', ['jpg','jpeg','png','gif']);
        $sizeMax = 1048576;

        $fileName = $files['name'];
        $fileTmp = $files['tmp_name'];
        $fileSize = $files['size'];
        $fileError = $files['error'];

        $fileExt = explode('.', $fileName);
        $fileExt = $fileExt[-1];
        $fileExt = strtolower($fileExt);

        $firstName = explode('.', $fileName);
        $firstName = $firstName[0];

        if (in_array($fileExt, ALLOWED_EXT)) {
            if (!$fileError) {
                if ($fileSize <= $sizeMax) {
                    $fileNameNew = uniqid(''). '&' . $firstName . '.' . $fileExt;
                    $fileDestination = $_SERVER['DOCUMENT_ROOT']  . '/uploads/' . $fileNameNew;

                    if (move_uploaded_file($fileTmp, $fileDestination)) {
                        $uploaded = $fileDestination;
                    } else {
                        $failed = "Une erreur est survenue lors de l'upload";
                    }
                } else {
                    $failed = "Fichier $fileName -> Taille limite [1 Mo] dépassée";
                }
            } else {
                $failed = "Une erreur est survenue lors de l'upload du fichier $fileName. Code erreur : $fileError";
            }
        } else {
            $failed = "L'extension $fileExt du fichier $fileName n'est pas autorisée";
        }
        return [$uploaded,$failed];
    }
}
