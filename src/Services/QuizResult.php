<?php

namespace App\Services;

class QuizResult
{
    public function calculate(array $answers, int $nbQuestion): int
    {
        $nbGoodAnswer = 0;

        foreach ($answers as $answer) {
            if ($answer == false) {
                $nbGoodAnswer++;
            }
        }
        // [Valeur 1] x 100 / [Valeur2] = [Résultat en %]
        return intval($nbGoodAnswer * 100 / $nbQuestion);
    }
}
