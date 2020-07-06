<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 100; $i++) {
            $user = new User();
            $user->setEmail('user'.$i.'@mail.com')
                ->setPassword($this->passwordEncoder->encodePassword($user, 'password'))
                ->setFirstname($faker->firstName)
                ->setLastname($faker->lastName);
            $manager->persist($user);
        }

        $admin = new User();
        $admin->setEmail('admin@mail.com')
            ->setPassword($this->passwordEncoder->encodePassword($admin, 'adminpassword'))
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstname('Jhonny')
            ->setLastname('Begood');
        $manager->persist($admin);

        $manager->flush();
    }
}
