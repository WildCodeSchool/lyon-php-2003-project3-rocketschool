<?php

namespace App\DataFixtures\Prod;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Vos entretiens entreprises');
        $manager->persist($category);
        $this->addReference('category_1', $category);

        $category2 = new Category();
        $category2->setName('Fonctionnement du bootcamp');
        $manager->persist($category2);
        $this->addReference('category_2', $category2);

        $category3 = new Category();
        $category3->setName('Votre contrat de travail');
        $manager->persist($category3);
        $this->addReference('category_3', $category3);

        $category4 = new Category();
        $category4->setName('Votre année d’alternance');
        $manager->persist($category4);
        $this->addReference('category_4', $category4);

        $category5 = new Category();
        $category5->setName('Le Campus');
        $manager->persist($category5);
        $this->addReference('category_5', $category5);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['groupProd'];
    }
}
