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
}
