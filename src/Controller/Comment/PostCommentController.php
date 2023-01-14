<?php

namespace App\Controller\Comment;

use App\Entity\Comment;
use App\Entity\Resource;
use App\Service\CommentManager;
use App\Service\ResourceManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;

#[AsController]
class PostCommentController extends AbstractController
{
    public function __construct(
        private ResourceManager $resourceManager,
        private EntityManagerInterface $entityManager,
        private CommentManager $commentManager,
    ){}

    public function __invoke(Comment $data): ?Comment
    {
        // If the user is null ask jwt to throw an exception
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Adding the user logged in & the resource to the new comment
        $data->setUser($this->getUser());

        // Creating the new comment
        $comment = $this->commentManager->createComment($data->getComment(), $data->getResource(), $this->getUser());

        return $comment;
    }
}