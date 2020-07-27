<?php

namespace App\Services;

use App\Entity\User;
use App\Repository\AccountsDurationRepository;
use App\Repository\UserRepository;
use DateTime;
use DateTimeInterface;

class UserManager
{
    private $durationRepository;

    public function __construct(AccountsDurationRepository $durationRepository)
    {
        $this->durationRepository = $durationRepository;
    }

    public function userCreation($accountsDuration)
    {
        $user = new User();
        if ($accountsDuration) {
            $days = $accountsDuration->getDays();
            $createdAt = $user->getCreatedAt();
            if ($createdAt) {
                $user->setDeletedAt($this->delAtCreation($user->getCreatedAt(), $days));
            }
        }

        return $user;
    }

    public function setDeletedAt($user)
    {
        $accountDuration = $this->durationRepository->findOneBy([]);

        $createdAt = $user->getCreatedAt();
        $user->setDeletedAt($this->delAtCreation($createdAt, $accountDuration->getDays()));
    }

    public function delAtCreation(?DateTimeInterface $createdAt, $days)
    {
        if ($createdAt) {
            $creationDate = $createdAt->format("d/m/Y");
            $modifiedDate = DateTime::createFromFormat('d/m/Y', $creationDate);

            if ($modifiedDate) {
                return date_modify($modifiedDate, "+$days days");
            }
        }
    }

//    public function updateDeletedAt(int $userId, string $deletedAt, UserRepository $userRepo)
//    {
//        $user = $userRepo->findOneBy(['id' => $userId]);
//        $deletedAt = DateTime::createFromFormat('Y-m-d', $deletedAt);
//        if ($user) {
//            $user->setDeletedAt($deletedAt);
//        }
//
//        return $user;
//    }
}
