<?php

namespace App\DataFixtures;

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
            ->setDescription("Vidéo Rocket School")
            ->setUpdatedAt(date_create('now'));
        $manager->persist($video);
        $manager->flush();
    }
}
