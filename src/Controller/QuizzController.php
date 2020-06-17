<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quizz;
use App\Entity\Reponse;
use App\Form\QuestionType;
use App\Form\QuizzType;
use App\Repository\QuestionRepository;
use App\Repository\QuizzRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quizz")
 */
class QuizzController extends AbstractController
{
    /**
     * @Route("/", name="quizz_index", methods={"GET"})
     * @param QuizzRepository $quizzRepository
     * @return Response
     */
    public function index(QuizzRepository $quizzRepository): Response
    {
        return $this->render('quizz/index.html.twig', [
            'quizzs' => $quizzRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="quizz_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $quizz = new Quizz();
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($quizz);
            $entityManager->flush();

            return $this->redirectToRoute('quizz_index');
        }

        return $this->render('quizz/new.html.twig', [
            'quizz' => $quizz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quizz_show", methods={"GET"})
     * @param Quizz $quizz
     * @param Question $question
     * @param Request $request
     * @param QuestionRepository $questionRepository
     * @return Response
     */
    public function show(
        Quizz $quizz,
        Question $question,
        Request $request,
        QuestionRepository $questionRepository
    ): Response {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);
        $questions = $questionRepository->findBy(array('quizz'=>$quizz));
        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index');
        }
        return $this->render('quizz/show.html.twig', [
            'quizz' => $quizz,
            'questions' => $questions,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="quizz_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Quizz $quizz
     * @return Response
     */
    public function edit(Request $request, Quizz $quizz): Response
    {
        $form = $this->createForm(QuizzType::class, $quizz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('quizz_index');
        }

        return $this->render('quizz/edit.html.twig', [
            'quizz' => $quizz,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="quizz_delete", methods={"DELETE"})
     * @param Request $request
     * @param Quizz $quizz
     * @return Response
     */
    public function delete(Request $request, Quizz $quizz): Response
    {
        if ($this->isCsrfTokenValid('delete'.$quizz->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($quizz);
            $entityManager->flush();
        }

        return $this->redirectToRoute('quizz_index');
    }
}
