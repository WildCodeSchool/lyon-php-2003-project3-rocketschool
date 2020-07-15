<?php

namespace App\DataFixtures;

use App\Entity\Checklist;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function getDependencies()
    {
        return [ProgramFixtures::class];
    }

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $checklist = new Checklist();
            $user = new User($checklist);
            $user->setEmail('user'.$i.'@mail.com')
                ->setProgram($this->getReference('First program'))
                ->setProgram($this->getReference('Second program'))
                ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName);
            $manager->persist($user);
        }


        $checklistAdmin = new Checklist();
        $admin = new User($checklistAdmin);
        $admin->setEmail('admin@mail.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'adminpassword'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Jhonny')
            ->setLastname('Begood');
        $manager->persist($admin);

        $manager->flush();
    }
}
