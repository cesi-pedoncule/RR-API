<?php

namespace App\Service;

use App\Entity\Resource;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class ResourceManager
{
    public function __construct(private EntityManagerInterface $em, private ValidationStateManager $validationStateManager)
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
}
