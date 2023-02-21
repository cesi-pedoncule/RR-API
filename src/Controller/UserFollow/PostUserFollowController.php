<?php

namespace App\Controller\UserFollow;

use App\Entity\UserFollow;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostUserFollowController extends AbstractController
{
    public function __invoke(UserFollow $data): ?UserFollow
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Adding the user logged in to the new userFollow
        $data->setFollower($this->getUser());

        return $data;
    }
}