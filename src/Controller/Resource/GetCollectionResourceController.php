<?php

namespace App\Controller\Resource;

use App\Service\ResourceManager;
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
        return $this->resourceManager->findPublicResources();
    }
}