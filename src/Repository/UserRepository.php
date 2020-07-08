<?php

namespace App\Repository;

use App\Entity\Program;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use DateTime;
use App\Repository\AccountsDurationRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * @var \App\Repository\AccountsDurationRepository
     */
    private $accDuRepo;

    public function __construct(ManagerRegistry $registry, AccountsDurationRepository $accDuRepo)
    {
        parent::__construct($registry, User::class);
        $this->accDuRepo = $accDuRepo;
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

    /**
     * @throws Exception
     */
    public function deleteOldAccounts(): void
    {
        $accountsDuration = $this->accDuRepo->findOneBy([]);
        if ($accountsDuration) {
            $days = $accountsDuration->getDays();
            $now = new DateTime();
            $before = date_modify($now, "-$days days");

            $qb = $this->getEntityManager()->createQueryBuilder();

            $qb->delete(User::class, 'u')
                ->where("u.createdAt < :before")
                ->setParameter('before', $before, \Doctrine\DBAL\Types\Types::DATETIME_MUTABLE)
                ->getQuery()->execute();
        }
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
