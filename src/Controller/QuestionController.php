<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quizz;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuizzRepository;
use App\Services\QuestionMoveItem;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/question")
 */
class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="question_index", methods={"GET"})
     * @param QuestionRepository $questionRepository
     * @return Response
     */
    public function index(QuestionRepository $questionRepository): Response
    {
        return $this->render('question/index.html.twig', [
            'questions' => $questionRepository->findBy([], ['questionOrder' => 'ASC']),
            'page_name' => 'Quizz - Edition',
        ]);
    }

    /**
     * @Route("/new", name="question_new", methods={"GET","POST"})
     * @param Request $request
     * @param QuizzRepository $quizzRepo
     * @param QuestionRepository $questionRepo
     * @return Response
     */
    public function new(Request $request, QuizzRepository $quizzRepo, QuestionRepository $questionRepo): Response
    {
        $question = new Question();
        $quizz = $quizzRepo->findOneBy([]);

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        $lastQuestion= $questionRepo
            ->findOneBy([], ['questionOrder' => 'DESC']);
        if ($lastQuestion != null) {
            $lastPosition = $lastQuestion->getQuestionOrder();
        } else {
            $lastPosition = -1;
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $question->setQuizz($quizz);
            $question->setQuestionOrder($lastPosition + 1);
            $entityManager->persist($question);
            $entityManager->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/new.html.twig', [
            'page_name' => 'Quizz - Ajouter une question',
            'question' => $question,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="question_show", methods={"GET"})
     * @param Question $question
     * @return Response
     */
    public function show(Question $question): Response
    {
        return $this->render('question/show.html.twig', [
            'question' => $question,
            'page_name' => 'Quizz - Question'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="question_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Question $question
     * @return Response
     */
    public function edit(Request $request, Question $question): Response
    {
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('question_index');
        }

        return $this->render('question/edit.html.twig', [
            'question' => $question,
            'form' => $form->createView(),
            'page_name' => "Quizz - Edition d'une question"
        ]);
    }

    /**
     * @Route("/{id}", name="question_delete", methods={"DELETE"})
     * @param Request $request
     * @param Question $question
     * @return Response
     */
    public function delete(Request $request, Question $question): Response
    {
        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($question);
            $entityManager->flush();
        }

        return $this->redirectToRoute('question_index');
    }

    /**
     * @Route("/move/{id}/{position}", name="question_move",methods={"GET", "POST"})
     * @return Response
     */
    public function move(QuestionMoveItem $questionMoveItem, Question $question, string $position): Response
    {
        $nbQuestion = count($this->getDoctrine()->getRepository(Question::class)->findAll());

        if ($question->getQuestionOrder() == 0 && $position == 'Up') {
            throw $this->createNotFoundException('Impossible de monter le premier élément');
        } elseif ($question->getQuestionOrder() == $nbQuestion - 1 && $position == 'Down') {
            throw $this->createNotFoundException('Impossible de descendre le dernier élément');
        } else {
            $questionMoveItem->move($question, $position);
        }

        return $this->redirectToRoute('question_index');
    }
}
