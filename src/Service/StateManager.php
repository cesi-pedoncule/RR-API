<?php

namespace App\Service;

use App\Entity\State;
use Doctrine\ORM\EntityManagerInterface;

class StateManager
{
    public function __construct(private EntityManagerInterface $manager)
    {
    }

    public function createDefaultStates(): void
    {
        $state_pending = new State();
        $state_pending->setLabel('pending');
        $this->manager->persist($state_pending);

        $state_validated = new State();
        $state_validated->setLabel('validated');
        $this->manager->persist($state_validated);

        $state_rejected = new State();
        $state_rejected->setLabel('rejected');
        $this->manager->persist($state_rejected);

        $this->manager->flush();
    }
}