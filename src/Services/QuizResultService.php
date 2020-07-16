<?php

namespace App\Services;

use App\Entity\QuizResult;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use DateTimeInterface;

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
        if (count($user->getQuizResult()) < 2) {
            $quizResult = new QuizResult();
            $quizResult->setUser($user)
                ->setResult($result)
                ->setAttempt(count($user->getQuizResult()) + 1);
            $this->entityManager->persist($quizResult);

            $this->validateCheckQuizz($quizResult, $user);

            $this->entityManager->flush();
        }
    }

    public function validateCheckQuizz(QuizResult $quizResult, User $user)
    {
        if (($quizResult->getAttempt() == 1 && $quizResult->getResult() == 100) || $quizResult->getAttempt() == 2) {
            $checklist = $user->getChecklist();
            if ($checklist) {
                $checklist->setCheckQuizz(true);
                $this->entityManager->persist($checklist);
            }
        }
    }

    public function isAllowed($user):bool
    {
        $checklist = $user->getChecklist();
        $roles = $user->getRoles();
        if ($checklist->getCheckVideo() || in_array("ROLE_ADMIN", $roles)) {
            return true;
        }
        return false;
    }
}
