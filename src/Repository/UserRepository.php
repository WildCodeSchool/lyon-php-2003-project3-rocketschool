<?php

namespace App\Repository;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use DateTime;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function search($keyword, $program): array
    {
        if (!empty($program)) {
            $query = $this->createQueryBuilder('User')
                ->andWhere('User.firstname like :keyword')
                ->orWhere('User.lastname like :keyword')
                ->orWhere('User.email like :keyword')
                ->andwhere('User.program = :program')
                ->setParameters([
                    'keyword' =>  $keyword . '%',
                    'program' => $program->getId(),
                ]);
        } else {
            $query = $this->createQueryBuilder('User')
                ->andWhere('User.firstname like :keyword')
                ->orWhere('User.lastname like :keyword')
                ->orWhere('User.email like :keyword')
                ->setParameter('keyword', $keyword . '%');
        }

        return $query->getQuery()->getResult();
    }

    public function deleteOldAccounts()
    {
//        $query =

        //$this->_em->createQueryBuilder('User')
//        $query = $this->createQueryBuilder('User')
//        ->delete('User', 'u')
//        ->andWhere('u.createdAt <  NOW() - INTERVAL 100 DAY')
//        ->getQuery()->execute();

//        return $query->getQuery()->getResult();
//        Ci-dessous : fonctionnel mais pas accepté par GrumPHP car format() retourne potentiellement false
//        $now = new DateTime;
//        $before = date_modify($now, '-100 days')->format('Y-m-d H:i:s');

        $now = new DateTime();
        //$before = date_modify($now, '-100 days')->format('Y-m-d H:i:s');
        $before = date_modify($now, '-100 days');
        //$qb->setParameter('date_from', $date_from, \Doctrine\DBAL\Types\Type::DATETIME);

        $qb = $this->getEntityManager()->createQueryBuilder();
            $qb->delete(User::class, 'u')
                ->where("u.createdAt < :before")
                ->setParameter('before', $before, \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)
                ->getQuery()->execute();

//        if ($before) {
//            $qb = $this->getEntityManager()->createQueryBuilder();
//            $qb->delete(User::class, 'u')
//                ->where("u.createdAt < '$before' -100")
//                ->getQuery()->execute();
//        }
        //$now->format('Y-m-d H:i:s');
        //$before = date_modify($now, '-100 days');
        //$qb = $this->getEntityManager()->createQueryBuilder();
        //$qb->delete(User::class, 'u')
        //->where("u.createdAt < '$now -100'")
        //->where("u.createdAt < NOW() - INTERVAL 100 DAYS")
        //->where("u.createdAt <  new DateTime(-100 days)")
        //->where("u.createdAt <  $now->format('Y-m-d H:i:s') INTERVAL 100 DAY")
        //->where("u.createdAt < date_format($now, 'Y-m-d H:i:s') INTERVAL 100 DAY")
        // CI-DESSOUS ÇA MARCHE !!!
        //->where("u.createdAt < '2019-08-06 17:32:53'")
        //->getQuery()->execute();
    }

    // /**
    //  * @return User[] Returns an array of User objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('u.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?User
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
