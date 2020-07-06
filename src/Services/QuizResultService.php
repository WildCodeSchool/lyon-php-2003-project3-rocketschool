<?php

namespace App\Services;

use App\Entity\QuizResult;
use Doctrine\ORM\EntityManagerInterface;

class QuizResultService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function calculate(array $answers, int $nbQuestion): int
    {
        $nbGoodAnswer = 0;

        foreach ($answers as $answer) {
            if ($answer == false) {
                $nbGoodAnswer++;
            }
        }
        return intval($nbGoodAnswer * 100 / $nbQuestion);
    }

    public function flush($user, $result)
    {
        if (count($user->getQuizResults()) < 2) {
            $quizResult = new QuizResult();
            $quizResult->setUser($user)
                ->setResult($result)
                ->setAttempt(count($user->getQuizResults()) + 1);
            $this->entityManager->persist($quizResult);
            $this->entityManager->flush();
        }
    }
}
