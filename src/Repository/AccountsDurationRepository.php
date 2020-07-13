<?php

namespace App\Repository;

use App\Entity\AccountsDuration;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AccountsDuration|null find($id, $lockMode = null, $lockVersion = null)
 * @method AccountsDuration|null findOneBy(array $criteria, array $orderBy = null)
 * @method AccountsDuration[]    findAll()
 * @method AccountsDuration[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AccountsDurationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AccountsDuration::class);
    }

    // /**
    //  * @return AccountsDuration[] Returns an array of AccountsDuration objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AccountsDuration
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
