<?php

namespace App\Services;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

class QuestionMoveItem
{
    private $questionRepository;

    private $entityManager;

    public function __construct(QuestionRepository $questionRepository, EntityManagerInterface $entityManager)
    {
        $this->questionRepository = $questionRepository;

        $this->entityManager = $entityManager;
    }

    /**
     * @param Question $question
     * @param string $direction
     * @return void
     */
    public function move(Question $question, string $direction): void
    {
        $currentPosition = $question->getQuestionOrder();

        if ($direction == 'Up') {
            $newQuestion = $this->questionRepository->findOneBy(['questionOrder' => $currentPosition - 1]);
        } else {
            $newQuestion = $this->questionRepository->findOneBy(['questionOrder' => $currentPosition + 1]);
        }

        $this->entityManager->persist($newQuestion);
        $question->setQuestionOrder($newQuestion->getQuestionOrder());
        $newQuestion->setQuestionOrder($currentPosition);
        $this->entityManager->persist($question);
        $this->entityManager->flush();
    }
}
