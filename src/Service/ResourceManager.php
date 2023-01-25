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
     * Return a resource by id if it is active
     * 
     * @param string $id
     * @return Resource|null
     */
    public function findResourceById(string $id): ?Resource
    {
        return $this->em->getRepository(Resource::class)->findOneBy(['id' => $id]);
    }

    /**
     * Return all Active resources
     * @return array<Resource>
     */
    public function findPublicResources(): array
    {
        return $this->em->getRepository(Resource::class)->findBy(['isPublic' => true]);
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
            ->setUpdatedAt(new \DateTimeImmutable());


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

    /**
     * Delete a resource and childs items from the database
     * 
     * @param Resource $resource
     * @return void
     */
    public function deleteResource(Resource $resource): void
    {
        // Delete all validation states of the resource
        $validationStates = $resource->getValidationStates();
        foreach ($validationStates as $validationState) {
            $this->em->remove($validationState);
        }

        // Delete all attachments of the resource
        $attachments = $resource->getAttachments();
        foreach ($attachments as $attachment) {
            $this->em->remove($attachment);
        }

        $this->em->remove($resource);
        $this->em->flush();
    }
}
