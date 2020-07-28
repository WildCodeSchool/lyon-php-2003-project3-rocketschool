<?php

namespace App\DataFixtures\Prod;

use App\Entity\Pdf;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class PdfFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $pdf = new Pdf();
        $pdf->setName('Guide entretien')
            ->setPath('guide_entretien.pdf');
        $manager->persist($pdf);
        $manager->flush();
    }
}
