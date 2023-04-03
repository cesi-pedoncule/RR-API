<?php

namespace App\Controller\Resource;

use App\Service\ResourceManager;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class GetCollectionResourceController extends AbstractController
{
    public function __construct(private ResourceManager $resourceManager)
    {
    }

    public function __invoke()
    {
        $resources = $this->resourceManager->findPublicResources();

        // Reverse all comments of all resources
        foreach ($resources as $resource) {
            $comments = array_reverse($resource->getComments()->toArray());
            $comments = new ArrayCollection($comments);
            $resource->setComments($comments);
        }

        return $resources;
    }
}