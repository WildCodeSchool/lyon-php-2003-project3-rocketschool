<?php

namespace App\DataFixtures\Prod;

use App\Entity\Video;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $video = new Video();
        $video->setName('Formation Rocket School')
            ->setEmbedUrl('https://www.youtube.com/embed/3ua1aWAUuWY')
            ->setDescription("Bienvenue,
            <br/><br/>L’objectif de cette vidéo est de vous préparer au mieux aux étapes qui vous attendent 
            pour intégrer Rocket School.
            <br/><br/>...Script de la vidéo en construction...")
            ->setUpdatedAt(date_create('now'));
        $manager->persist($video);
        $manager->flush();
    }
}
