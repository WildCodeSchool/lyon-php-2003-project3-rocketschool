<?php

namespace App\Services;

use App\Entity\Faq;
use App\Repository\FaqRepository;
use Doctrine\ORM\EntityManagerInterface;

class FaqMoveItem
{
    private $faqRepository;

    private $entityManager;

    public function __construct(FaqRepository $faqRepository, EntityManagerInterface $entityManager)
    {
        $this->faqRepository = $faqRepository;

        $this->entityManager = $entityManager;
    }

    /**
     * @param Faq $faq
     * @param string $direction
     * @return void
     */
    public function move(Faq $faq, string $direction): void
    {
        $currentPosition = $faq->getPosition();

        if ($direction == 'Up') {
            $newFaq = $this->faqRepository->findOneBy(['position' => $currentPosition - 1]);
        } else {
            $newFaq = $this->faqRepository->findOneBy(['position' => $currentPosition + 1]);
        }

        $this->entityManager->persist($newFaq);
        $faq->setPosition($newFaq->getPosition());
        $newFaq->setPosition($currentPosition);
        $this->entityManager->persist($faq);
        $this->entityManager->flush();
    }
}
