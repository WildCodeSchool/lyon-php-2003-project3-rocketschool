<?php

namespace App\DataFixtures;

use App\Entity\AccountsDuration;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AccountsDurationFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $accountsDuration = new AccountsDuration();
        $accountsDuration->setDays(100);
        $this->addReference('AccountsDuration', $accountsDuration);

        $manager->persist($accountsDuration);
        $manager->flush();
    }
}
