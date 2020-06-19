<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Commerce');
        $manager->persist($category);
        $this->addReference('category_1', $category);
        $category2 = new Category();
        $category2->setName('Marketing');
        $manager->persist($category2);
        $this->addReference('category_2', $category2);
        $manager->flush();
    }
}
