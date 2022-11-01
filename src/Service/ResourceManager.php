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
}
