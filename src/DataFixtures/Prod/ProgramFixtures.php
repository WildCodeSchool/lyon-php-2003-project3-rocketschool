<?php

namespace App\DataFixtures\Prod;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $program = new Program();
        $program->setName('Customer Success Manager');
        $this->addReference('First program', $program);
        $manager->persist($program);

        $program = new Program();
        $program->setName('Business Developer');
        $this->addReference('Second program', $program);
        $manager->persist($program);


        $program = new Program();
        $program->setName('Marketing Manager');
        $this->addReference('Third program', $program);

        $manager->persist($program);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupProd'];
    }
}
