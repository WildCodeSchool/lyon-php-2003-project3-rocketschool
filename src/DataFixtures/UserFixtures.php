<?php

namespace App\DataFixtures;

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

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $checklist = new Checklist();
            $user = new User($checklist);
            $user->setEmail('user'.$i.'@mail.com')
                ->setProgram($this->getReference('First program'))
                ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName)
                ->setAccountsDuration($this->getReference('AccountsDuration'));
            $manager->persist($user);
            $manager->flush();

            $userManager->setDeletedAt($user, $user->getAccountsDuration()->getDays());
            $manager->persist($user);
        }


//        $userManager = new UserManager();
//        $accountDuration = new AccountsDuration();
//        $accountDuration->setDays(100);

        $admin = new User();
        $admin->setEmail('admin@mail.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'adminpassword'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Jhonny')
            ->setLastname('Begood');
//        ->setAccountsDuration($accountDuration);
        $manager->persist($admin);

        $manager->flush();
    }
}
