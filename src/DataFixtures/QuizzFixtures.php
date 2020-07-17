<?php

namespace App\DataFixtures;

use App\Entity\Proposition;
use App\Entity\Question;
use App\Entity\Quizz;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

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
            "L'Account Executive" => [
                'isGood' => 0,
            ],
        ],
        "Qu'est-ce qu'un CRM ?" =>[
            "Un outil qui permet de gérer la relation avec les clients"=> [
                'isGood' => 1,
            ],
            "Une personne qui s'occupe d'accompagner les clients"=> [
                'isGood' => 0,
            ],

        ],
        "Qu'est-ce que l'inbound ?" =>[
            "Le prospect vient à l'entreprise"=> [
                'isGood' => 1,
            ],
            "Le commercial va au prospect"=> [
                'isGood' => 0,
            ],

        ],
        "Un persona, c'est :" =>[
            "Un prospect pour lequel on a toutes les informations de contact"=> [
                'isGood' => 0,
            ],
            "Une personne fictive représentant un profil de prospect"=> [
                'isGood' => 1,
            ],
            "Un prospect dont on a le nom mais pas les informations de contact"=> [
                'isGood' => 0,
            ],
        ],
    ];


    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        $quizz = new Quizz();
        $quizz->setTitle('Quizz de test')
            ->setIsEnable(true);

        $nbrQ = 0;
        foreach (self::QUESTIONS as $title => $propositions) {
            $question = new Question();
            $question->setQuizz($quizz);
            $question->setTitle($title);
            $question->setSolution($faker->sentence);
            $question->setQuestionOrder($nbrQ);
            $manager->persist($question);
            $nbrQ++;

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
