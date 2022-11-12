<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\ValidationState;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ValidationStateManager
{
    public function __construct(private EntityManagerInterface $em, private StateManager $stateManager)
    {
    }

    /**
     * Create a new validation state
     *
     * @param integer $validationState
     * @param Resource $resource
     * @param User $moderator
     * @return ValidationState|null
     */
    public function addValidationState(int $validationState, Resource $resource, User $moderator): ?ValidationState
    {
        $validationState = (new ValidationState())
            ->setState($this->stateManager->getStateById($validationState))
            ->setModerator($moderator)
            ->setResource($resource)
            ->setUpdatedAt(new DateTimeImmutable());

        $this->em->persist($validationState);
        $this->em->flush();

        return $validationState;
    }
}