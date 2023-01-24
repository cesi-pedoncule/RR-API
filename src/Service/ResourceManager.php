<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\User;
use App\Entity\ValidationState;
use Doctrine\ORM\EntityManagerInterface;

class ResourceManager
{
    public function __construct(private EntityManagerInterface $em, private ValidationStateManager $validationStateManager, private StateManager $stateManager)
    {
    }

    /**
     * Find a resource by title property
     *
     * @param string $title
     * @return Resource|null
     */
    public function findResourceByTitle(string $resourceTitle): ?Resource
    {
        return $this->em->getRepository(Resource::class)->findOneBy(['title' => $resourceTitle]);
    }

    /**
     * Return all Active resources
     * @return array<Resource>
     */
    public function findPublicActivesResources(): array
    {
        return $this->em->getRepository(Resource::class)->findBy(['isPublic' => true, 'isDeleted' => false]);
    }

    /**
     * Create a new resource
     *
     * @param string $title
     * @param string|null $description
     * @param array $attachments
     * @param boolean $isPublic
     * @param array $categories
     * @return Resource|null
     */
    public function createResource(string $title, ?string $description, array $attachments, bool $isPublic, array $categories, User $moderator): ?Resource
    {
        // Check if the resource already exists
        if ($this->findResourceByTitle($title) !== null) {
            return null;
        }

        $resource = (new Resource())
            ->setTitle($title)
            ->setDescription($description)
            ->setIsPublic($isPublic)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
            ->setIsDeleted(false);


        foreach ($attachments as $attachment) {
            $resource->addAttachment($attachment);
        }

        foreach ($categories as $category) {
            $resource->addCategory($category);
        }

        $validationState = $this->validationStateManager->addValidationState(1, $resource, $moderator);

        $this->em->persist($resource);
        $this->em->flush();

        return $resource;
    }

    /**
     * Disable a resource
     * 
     * @param User $user
     * @param Resource $resource
     * @return Resource|null
     */
    public function disableResource(User $user, Resource $resource): ?Resource
    {
        // Check if the user is the resource owner or an admin
        if (in_array('ROLE_ADMIN', $user->getRoles()) || $resource->getUser() === $user) {
            $resource->setIsDeleted(true);
            $resource->setUpdatedAt(new \DateTimeImmutable());
            // Delete all validation states
            foreach ($resource->getValidationStates() as $validationState) {
                $resource->removeValidationState($validationState);
            }
            // Delete all comments
            foreach ($resource->getComments() as $comment) {
                $resource->removeComment($comment);
            }
            $this->em->persist($resource);
            $this->em->flush();
            return $resource;
        }
        return null;
    }

    public function createDefaultValidationState(Resource $resource): ?ValidationState
    {
        $validationState = (new ValidationState())
            ->setResource($resource)
            ->setState($this->stateManager->getStateByLabel('pending'))
            ->setUpdatedAt(new \DateTimeImmutable());

        $this->em->persist($validationState);
        $this->em->flush();

        return $validationState;
    }
}
