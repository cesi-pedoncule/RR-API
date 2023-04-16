<?php

namespace App\DataFixtures;

use App\Service\UserManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class UserLikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private UserManager $userManager)
    {
    }

    public function load(ObjectManager $manager)
    {
        // Create 10 fake users likes
        for ($i = 1; $i <= 10; $i++) {
            $this->userManager->likeResource($this->getReference('user_0'), $this->getReference('resource_' . $i));
        }
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ResourceFixtures::class,
        ];
    }
}