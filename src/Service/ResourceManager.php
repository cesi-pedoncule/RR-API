<?php

namespace App\Service;

use App\Entity\Resource;
use Doctrine\ORM\EntityManagerInterface;

class ResourceManager
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function findResourceByTitle(string $resourceTitle): ?Resource
    {
        return $this->em->getRepository(Resource::class)->findOneBy(['title' => $resourceTitle]);
    }

    public function createResource(string $title, ?string $description, array $attachments, bool $isPublic, array $categories): ?Resource
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

        $this->em->persist($resource);
        $this->em->flush();

        return $resource;
    }
}
