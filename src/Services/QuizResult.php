<?php

namespace App\Services;

use Doctrine\ORM\EntityManagerInterface;

class QuizResult
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
        if ($user->getQuizResult1() == null) {
            $user->setQuizResult1($result);
        } elseif ($user->getQuizResult2() == null) {
            $user->setQuizResult2($result);
        }
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}
