<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher)
    {  
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        // Create 10 fake users
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@example.com')
                ->setPassword('password')
                ->setName($faker->lastname())
                ->setFirstname($faker->firstname())
                ->setRoles(['ROLE_USER'])
                ->setIsBanned(false);

            if ($i == 0) {
                $user->setRoles(['ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MODERATOR']);
            } elseif ($i == 1) {
                $user->setRoles(['ROLE_ADMIN', 'ROLE_MODERATOR']);
            } elseif ($i == 2) {
                $user->setRoles(['ROLE_MODERATOR']);
            }

            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();
    }
}