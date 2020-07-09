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
            if (isset($_POST['title']) || isset($_POST['content'])) {
                $title = self::cleanInput($_POST['title']);
                $content = self::cleanInput($_POST['content']);

                $note = new Note();
                $note->setTitle($title);
                $note->setContent($content);
                $note->setUser($this->getUser());
                $entityManager->persist($note);
            } else {
                $firstname = self::cleanInput($_POST['firstname']);
                $lastname = self::cleanInput($_POST['lastname']);
                $email = self::cleanInput($_POST['email']);
//                $program = $_POST['program'];

                $user = $this->getUser();
                $user->setFirstname($firstname);
                $user->setLastname($lastname);
                $user->setEmail($email);
//                $user->setProgram($program);
                if ($_FILES && !empty($_FILES['name'])) {
                    list($uploaded, $failed) = self::uploadPhoto($_FILES);

                    if (!empty($failed)) {
                        $this->addFlash('danger', $failed);
                        return $this->render('account/index.html.twig', [
                            'controller_name' => 'AccountController', 'page_name' => 'Mon Profil'
                        ]);
                    }
                    $user->setImage($uploaded);
                }
                $entityManager->persist($user);
            }
            $entityManager->flush();
        }

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController', 'page_name' => 'Mon Profil'
        ]);
    }

//    /**
//     * @Route("/account/{note<\d+>}", name="edit_note")
//     * @param Note $note
//     * @param EntityManagerInterface $entityManager
//     * @return Response
//     */
//    public function edit(Note $note, EntityManagerInterface $entityManager):Response
//    {
//        return $this->render('account/index.html.twig', ['note' => $note, 'page_name' => 'Mon Profil']);
//    }

    /**
     * @Route("/account/{note<\d+>}", name="delete_note")
     * @param Note $note
     * @param EntityManagerInterface $entityManager
     */
    public function delete(Note $note, EntityManagerInterface $entityManager)
    {
        $entityManager->remove($note);
        $entityManager->flush();

        return $this->redirectToRoute('profil');
    }

    public static function cleanInput(string $input):string
    {
        $input = trim($input);
        $input = stripslashes($input);
        $input = htmlspecialchars($input);

        return $input;
    }

    public static function uploadPhoto($image):array
    {
        $image = $image['image'];

        $publicPath = '';
        $failed = '';

        define('ALLOWED_EXT', ['jpg','jpeg','png','gif']);
        $sizeMax = 1048576;

        $fileName = $image['name'];
        $fileTmp = $image['tmp_name'];
        $fileSize = $image['size'];
        $fileError = $image['error'];

        $fileExt = explode('.', $fileName);
        $fileExt = strval(end($fileExt));
        $fileExt = strtolower($fileExt);

        $firstName = explode('.', $fileName);
        $firstName = $firstName[0];

        if (in_array($fileExt, ALLOWED_EXT)) {
            if (!$fileError) {
                if ($fileSize <= $sizeMax) {
                    $fileNameNew = uniqid(''). '&' . $firstName . '.' . $fileExt;
                    $fileDestination = $_SERVER['DOCUMENT_ROOT']  . '/uploads/' . $fileNameNew;

                    if (move_uploaded_file($fileTmp, $fileDestination)) {
                        $publicPath = 'uploads/'. $fileNameNew;
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

        return [$publicPath,$failed];
    }
}
