<?php

namespace App\Controller\UserLike;

use App\Entity\UserLike;
use App\Service\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostUserLikeController extends AbstractController
{
    public function __construct(private UserManager $userManager)
    {
    }

    public function __invoke(UserLike $data): UserLike|JsonResponse
    {
        // Check if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get the user
        $user = $this->getUser();

        try {
            // Check if the user already liked the resource
            return $this->userManager->likeResource($user, $data->getResource());
        } catch (\Throwable $th) {
            //throw $th;
            return new JsonResponse(['error' => $th->getMessage()], 400);
        }
    }
}