<?php

namespace App\Repository;

use App\Entity\Category;
use App\Entity\Faq;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Faq|null find($id, $lockMode = null, $lockVersion = null)
 * @method Faq|null findOneBy(array $criteria, array $orderBy = null)
 * @method Faq[]    findAll()
 * @method Faq[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FaqRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Faq::class);
    }

    /**
     * @param string $keyword
     * @param Category $category
     * @return Faq[]
     */
    public function findBySomeField($keyword, $category): array
    {
        if (!empty($category)) {
            $query = $this->createQueryBuilder('f')
                ->andWhere('f.question like :keyword')
                ->orWhere('f.answer like :keyword')
                ->andwhere('f.category = :category')
                ->setParameters([
                    'keyword' => '%' . $keyword . '%',
                    'category' => $category->getId(),
                ]);
        } else {
            $query = $this->createQueryBuilder('f')
                ->andWhere('f.question like :keyword')
                ->orWhere('f.answer like :keyword')
                ->setParameter('keyword', '%' . $keyword . '%');
        }

        return $query->getQuery()->getResult();
    }


    // /**
    //  * @return Faq[] Returns an array of Faq objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
