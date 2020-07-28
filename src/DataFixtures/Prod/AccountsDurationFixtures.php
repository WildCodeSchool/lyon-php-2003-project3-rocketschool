<?php

namespace App\DataFixtures\Prod;

use App\Entity\AccountsDuration;
use App\Entity\Program;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class AccountsDurationFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $accountsDuration = new AccountsDuration();
        $accountsDuration->setDays(100);
        $this->addReference('AccountsDuration', $accountsDuration);

        $manager->persist($accountsDuration);
        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupProd'];
    }
}
