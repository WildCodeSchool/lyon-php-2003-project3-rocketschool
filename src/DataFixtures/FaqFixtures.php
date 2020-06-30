<?php

namespace App\DataFixtures;

use App\Entity\Faq;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker;

class FaqFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [CategoryFixtures::class];
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

        for ($i = 0; $i < 5; $i++) {
            $faq = new Faq();
            $faq->setQuestion($faker->sentence)
                ->setAnswer($faker->paragraph(3))
                ->setCreatedAt(date_create('now'));
            $manager->persist($faq);
        }
        for ($i = 0; $i < 5; $i++) {
            $faq = new Faq();
            $faq->setQuestion($faker->sentence)
                ->setAnswer($faker->paragraph(3))
                ->setCreatedAt(date_create('now'))
                ->setCategory($this->getReference('category_1'));
            $manager->persist($faq);
        }
        for ($i = 0; $i < 5; $i++) {
            $faq = new Faq();
            $faq->setQuestion($faker->sentence)
                ->setAnswer($faker->paragraph(3))
                ->setCreatedAt(date_create('now'))
                ->setCategory($this->getReference('category_2'));
            $manager->persist($faq);
        }
        $manager->flush();
    }
}
