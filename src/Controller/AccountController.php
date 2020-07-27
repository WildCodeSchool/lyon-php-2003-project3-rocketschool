<?php

namespace App\Controller;

use App\Entity\Note;
use App\Entity\User;
use App\Repository\ProgramRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccountController extends AbstractController
{
    /**
     * @Route("/account", name="profil")
     * @param EntityManagerInterface $entityManager
     * @param ProgramRepository $programRepo
     * @param UserRepository $userRepository
     * @return Response
     */
    public function index(
        EntityManagerInterface $entityManager,
        ProgramRepository $programRepo,
        UserRepository $userRepository
    ):Response {
        if ($_POST) {
            $user = $userRepository->find($_POST['userId']);

            if ($user) {
                // New note
                if (isset($_POST['title']) || isset($_POST['content'])) {
                    $title = self::cleanInput($_POST['title']);
                    $content = self::cleanInput($_POST['content']);

                    $note = new Note();
                    $note->setTitle($title);
                    $note->setContent($content);
                    $note->setUser($user);
                    $entityManager->persist($note);
                } else {
                    // Profil edition
                    $firstname = self::cleanInput($_POST['firstname']);
                    $lastname = self::cleanInput($_POST['lastname']);
                    $email = self::cleanInput($_POST['email']);
                    $program = $programRepo->findOneBy($_POST['program']);

                    $inputs = [$firstname,$lastname,$email,$program];

                    $errors = self::checkErrors($inputs);

                    if ($errors) {
                        return $this->redirectToRoute('profil');
                    }

                    $user = self::update($user, $inputs);
                    $entityManager->persist($user);
                }
                $entityManager->flush();
            }
        }

        $programs = $programRepo->findAll();

        return $this->render('account/index.html.twig', [
            'controller_name' => 'AccountController', 'programs' => $programs, 'page_name' => 'Profil'
        ]);
    }

    /**
     * @Route("/account/{note<\d+>}", name="delete_note")
     * @param Note $note
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function delete(Note $note, EntityManagerInterface $entityManager):Response
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

    public function checkErrors(array $inputs)
    {
        $errors = [];

        if (empty($inputs[0])) {
            $this->addFlash('danger', "Veuillez renseigner un prénom svp");
            $errors['firstname'] = true;
        }
        if (empty($inputs[1])) {
            $this->addFlash('danger', "Veuillez renseigner un nom svp");
            $errors['lastname'] = true;
        }
        if (empty($inputs[2])) {
            $this->addFlash('danger', "Email obligatoire");
            $errors['email'] = true;
        }
        return $errors;
    }

    public function update(User $user, array $inputs)
    {
        list($firstname, $lastname, $email, $program) = $inputs;

        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);
        $user->setProgram($program);
        if ($_FILES && !empty($_FILES['image']['name'])) {
            list($uploaded, $failed) = self::uploadPhoto($_FILES);

            if (!empty($failed)) {
                $this->addFlash('danger', $failed);
                return $this->redirectToRoute('profil');
            }
            $user->setImage($uploaded);
        }

        return $user;
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
