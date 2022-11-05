<?php

namespace App\DataFixtures;

use App\Entity\State;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class StateFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $state_pending = new State();
        $state_pending->setLabel('pending');
        $manager->persist($state_pending);

        $state_validated = new State();
        $state_validated->setLabel('validated');
        $manager->persist($state_validated);

        $state_rejected = new State();
        $state_rejected->setLabel('rejected');
        $manager->persist($state_rejected);

        $manager->flush();
        
        $this->addReference('state_pending', $state_pending);
        $this->addReference('state_validated', $state_validated);
        $this->addReference('state_rejected', $state_rejected);
    }
}