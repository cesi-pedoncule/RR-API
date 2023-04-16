<?php

namespace App\DataFixtures;

use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFollowFixtures extends Fixture
{
    public function __construct(private UserManager $userManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        // Create 10 fake users
        for ($i = 1; $i <= 10; $i++) {
            $this->userManager->followUser($this->getReference('user_0'), $this->getReference('user_' . rand(1, 9)));
        }
    }
}