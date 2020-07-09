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
        "Qu'est-ce que le SEO ?" =>[
            "En français l'Optimisation du Standard Tel, autrement dit toute la gestion des appels entrants"=> [
                'isGood' => 0,
            ],
            "En français l'Optimisation pour les Moteurs de Recherche, autrement dit le fait d'optimiser
             son site internet et ses contenus pour qu'ils soient bien référencés"=> [
                'isGood' => 1,
            ],
            "En français l'Optimisation de ses Campagnes Payantes, autrement dit un outil qui permet de s'assurer 
            qu'on a un retour sur investissement sur ses différents canaux d'acquisition payants"=> [
                'isGood' => 0,
            ],

        ],
        "Le Growth Hacker doit (plusieurs réponses possibles) : " =>[
            "S'assurer qu'un prospect qui a un besoin correspondant à la solution de son entreprise
             la trouve facilement"=> [
                'isGood' => 1,
            ],
            "Entrer en contact avec les prospects qu'il a identifiés"=> [
                'isGood' => 0,
            ],
            "Garantir que les clients sont satisfaits tout au long de leur expérience"=> [
                'isGood' => 1,
            ],
            "Automatiser des actions et mails pour faciliter la prise de contact des prospects avec l'entreprise"=> [
                'isGood' => 0,
            ],

        ],
        "Le Bizdev doit (plusieurs réponses possibles) :" =>[
            "Faire des campagnes d'acquisition payante pour se faire connaître auprès de prospects"=> [
                'isGood' => 0,
            ],
            "Constituer des listes de cibles et les enrichir avec leurs données de contact pour pouvoir
             entrer en contact direct avec eux"=> [
                'isGood' => 1,
            ],
            "Mettre en place un onboarding pour les nouveaux clients, pour faciliter leur appropriation 
            de la solution"=> [
                'isGood' => 0,
            ],
            "Automatiser des séquences de mails, pour faire de la prospection"=> [
                'isGood' => 1,
            ],
            "Automatiser des séquences de mails, pour faire du nurturing"=> [
                'isGood' => 1,
            ],
        ],
        "Le social selling, c'est : " =>[
            "Avoir un réseau important pour vendre par bouche à oreilles"=> [
                'isGood' => 0,
            ],
            "Être premier dans les recherches Google"=> [
                'isGood' => 0,
            ],
            "Utiliser les réseaux sociaux pour son acte de vente"=> [
                'isGood' => 1,
            ],

        ],
        "Le pipe, c'est :" =>[
            "L'ensemble des phases par lesquels passe un prospect / un client dans sa vie avec l'entreprise"=> [
                'isGood' => 1,
            ],
            "Le support qui permet de faire qu'un client va bien prendre en main la solution"=> [
                'isGood' => 0,
            ],
            "Le fait de concentrer ses efforts sur les clients en fonction de leur potentiel de chiffre d'affaires"=> [
                'isGood' => 0,
            ],

        ],
        "Le rôle du Customer Success Manager, ou CSM, c'est (plusieurs réponses possibles) :" =>[
            "D'accompagner les clients dans leur prise en main"=> [
                'isGood' => 1,
            ],
            "De réaliser des séquences de mail pour prendre contact avec des prospects"=> [
                'isGood' => 0,
            ],
            "De garantir la satisfaction client"=> [
                'isGood' => 1,
            ],
            "De réaliser des séquences de mail pour informer les clients notamment sur de nouvelles fonctionnalités"=> [
                'isGood' => 1,
            ],

        ],
        "Le CSM n'a pas de rôle de vente" =>[
            "Vrai, il a juste un rôle d'accompagnement"=> [
                'isGood' => 0,
            ],
            "Faux, il doit quand même faire prospérer son portefeuille client"=> [
                'isGood' => 1,
            ],

        ],
        "Pour intégrer la formation Rocket School gratuitement, il faut impérativement
         (plusieurs réponses possibles) : " =>[
            "Parler anglais"=> [
                'isGood' => 0,
            ],
            "Avoir la promesse d'embauche d'une entreprise avant le début du bootcamp"=> [
                'isGood' => 1,
            ],
            "Être âgé de plus de 25 ans"=> [
                'isGood' => 0,
            ],
            "Être inscrit à Pôle Emploi"=> [
                'isGood' => 1,
            ],

        ],
        "Le bootcamp Rocket School, c'est : " =>[
            "12 semaines intensives de formation très théorique, avec de grands intervenants qui donnent des cours"=> [
                'isGood' => 0,
            ],
            "12 semaines intensives de formation très opérationnelle, avec des challenges et de la mise en pratique"=> [
                'isGood' => 1,
            ],

        ],
        "Les contrats possibles après le bootcamp, en fonction des entreprises, sont
         (plusieurs réponses possibles) :" =>[
            "en CDI"=> [
                'isGood' => 1,
            ],
            "en CDD 12 mois minimum"=> [
                'isGood' => 1,
            ],
            "en contrat de professionnalisation, avec 4 jours en entreprise et 1 jour à l'école par semaine"=> [
                'isGood' => 1,
            ],
            "en contrat d'apprentissage, avec 4 jours en entreprise et 1 jour à l'école par semaine"=> [
                'isGood' => 0,
            ],
            "en Freelance"=> [
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
