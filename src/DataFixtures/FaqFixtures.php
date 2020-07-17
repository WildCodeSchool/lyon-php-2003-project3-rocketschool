<?php

namespace App\DataFixtures;

use App\Entity\Faq;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FaqFixtures extends Fixture implements DependentFixtureInterface
{

    const FAQ = [
        'Combien d’entretiens puis-je passer ?' =>
            'Une fois sélectionné.e, nous vous présenterons à un maximum d\'entreprises 
            dans le but de matcher avec l\'une d\'entre elles. Nous vous encourageons vivement à postuler également 
            de votre côté. Cela est complémentaire et va maximiser vos chances de décrocher une 
            promesse d\'embauche.',
        'Comment est financée ma formation bootcamp ?' =>
            'Le bootcamp est une Préparation Opérationnelle à l’Emploi 
            Individuel. Le financement du bootcamp est assuré',
        'Que dois-je renseigner lors de mon actualisation sur le site de Pôle Emploi pendant le bootcamp ?' =>
            'Pour la première actualisation, indiquez que vous êtes toujours en recherche d’emploi, que vous n’avez 
            pas travaillé et que vous n’avez pas suivi de formation en attendant la codification des dossiers de 
            formation Pôle Emploi. Tant que la question de la recherche d’emploi apparaît lors de l’actualisation, 
            continuez à indiquer que vous êtes en recherche d’emploi afin de ne pas être radié.e par PE.',
        'Que se passe-t-il si je suis absent pendant le bootcamp?' =>
            'Vous êtes tenu.e d’être présent.e et de participer tous les jours pendant le bootcamp. Vous signerez une 
            feuille de présence chaque demi-journée qui sera remise à Pôle Emploi et vous permettra 
            de toucher l’intégralité de votre rémunération de formation. Toute absence devra être 
            justifiée par un justificatif officiel (certificat médical, absence pour un événement 
            avec votre entreprise justifiée par votre tuteur, etc.). En cas d’absence injustifiée, 
            vous serez convoqué par l’équipe pédagogique pour vous expliquer. Après 2 absences injustifiées, 
            votre entreprise d’accueil sera prévenue et il sera décidé avec elle si vous pouvez continuer la 
            formation. 3 absences injustifiées signifient votre renvoi de la formation. ',
        'Quel sera mon statut pendant le bootcamp ?' =>
            'Vous avez un statut de demandeur d’emploi, vous pouvez donc toucher des aides financières comme les ARE, 
            le RSA etc. De plus vous pouvez bénéficier de certaines réductions notamment pour les transports 
            (cf abonnements Solidaires TCL).'
    ];

    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {

        $categories = [
            "",
            $this->getReference('category_1'),
            $this->getReference('category_2')
        ];

        $index = 0;
        foreach (self::FAQ as $question => $answer) {
            $faq = new Faq();
            $faq->setQuestion($question)
                ->setAnswer($answer)
                ->setCreatedAt(date_create('now'))
                ->setCategory($categories[rand(0, 2)])
                ->setPosition($index);
            $index++;
            $manager->persist($faq);
        }
        $manager->flush();
    }
}
