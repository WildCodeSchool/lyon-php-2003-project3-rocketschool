<?php

namespace App\DataFixtures;

use App\DataFixtures\Prod\AccountsDurationFixtures;
use App\DataFixtures\Prod\ProgramFixtures;
use App\Entity\AccountsDuration;
use App\Entity\Checklist;
use App\Entity\User;
use App\Services\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class, AccountsDurationFixtures::class];
    }

    private $passwordEncoder;

    private $userManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, UserManager $userManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->userManager = $userManager;
    }

    public function load(ObjectManager $manager)
    {
        $program = ['First program', 'Second program', 'Third program'];
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 25; $i++) {
            $checklist = new Checklist();
            $user = new User($checklist);
            $user->setEmail('user'.$i.'@mail.com')
                ->setProgram($this->getReference($program[rand(0, 2)]))
                ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setAccountsDuration($this->getReference('AccountsDuration'));
            $manager->persist($user);
            $manager->flush();
            $this->userManager->setDeletedAt($user);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin@mail.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'adminpassword'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Admin')
            ->setLastname('Admin');
        $manager->persist($admin);
        $manager->flush();
    }
}
