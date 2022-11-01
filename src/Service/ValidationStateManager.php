<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\ValidationState;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;

class ValidationStateManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function addValidationState(int $validationState, Resource $resource, User $moderator): void
    {
        $validationState = (new ValidationState())
            ->setState($validationState)
            ->setModerator($moderator)
            ->setResource($resource)
            ->setUpdatedAt(new DateTimeImmutable());

        $this->em->persist($validationState);
        $this->em->flush();
    }
}