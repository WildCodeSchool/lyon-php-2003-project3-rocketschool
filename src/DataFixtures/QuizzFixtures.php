<?php

namespace App\DataFixtures;

use App\Entity\Proposition;
use App\Entity\Question;
use App\Entity\Quizz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class QuizzFixtures extends Fixture
{
    const QUESTIONS = [
        "Lequel de ces exemples illustre le BtoB ?" => [
            "Panzani qui vend des pâtes à Casino qui les met en rayon pour les consommateurs" => [
                'isGood' => 0,
            ],
            "Aircall qui vend sa solution de téléphonie à d'autres entreprises qui la mettent à disposition 
            de leurs collaborateurs" => [
                'isGood' => 1,
            ],
            "H&M, qui vend ses vêtements et accessoires à des particuliers" => [
                'isGood' => 0,
            ],

        ],
        "Qu'est ce qu'un lead" => [
            "Un client potentiel" => [
                'isGood' => 0,
            ],
            "Un client actuel" => [
                'isGood' => 0,
            ],
            "Un ancien client qu'on veut reconquérir" => [
                'isGood' => 1,
            ],
        ],
        "Quel métier est centré sur le marketing digital ?" => [
            "Le Growth Hacker" => [
                'isGood' => 1,
            ],
            "Le Customer Success Manager" => [
                'isGood' => 1,
            ],
            "Le Business Developer" => [
                'isGood' => 0,
            ],
        ]
    ];


    public function load(ObjectManager $manager)
    {
        $quizz = new Quizz();
        $quizz->setTitle('Quizz de test')
            ->setIsEnable(true);


        foreach (self::QUESTIONS as $title => $propositions) {
            $question = new Question();
            $question->setQuizz($quizz);
            $question->setTitle($title);
            $manager->persist($question);

            foreach ($propositions as $title => $data) {
                $proposition = new Proposition();
                $proposition->setTitle($title)
                    ->setIsGood($data['isGood'])
                    ->setQuestion($question);
                $manager->persist($proposition);
            }
        }

        $manager->persist($quizz);
        $manager->flush();
    }
}
