<?php 

namespace App\Controller\UserFollow;

use App\Entity\UserFollow;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class DeleteUserFollowController extends AbstractController
{
    public function __invoke(UserFollow $data): UserFollow|JsonResponse|null
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Check if the user is the follower of the userFollowed
        if ($data->getFollower() !== $this->getUser()) {
            return new JsonResponse(['error' => 'You can\'t unfollow this user'], 400);   
        }

        return $data;
    }
}