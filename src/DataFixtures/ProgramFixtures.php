<?php

namespace App\DataFixtures;

use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ProgramFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $program = new Program();
        $program->setName('Digital business');
        $this->addReference('First program', $program);
        $manager->persist($program);

        $program = new Program();
        $program->setName('Digital marketing');
        $this->addReference('Second program', $program);

        $manager->persist($program);
        $manager->flush();
    }
}
