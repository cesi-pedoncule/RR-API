<?php

namespace App\Service;

use App\Entity\Comment;
use App\Entity\Resource;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class CommentManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Find a comment by id
     *
     * @param string $id
     * @return Comment|null
     */
    public function findCommentById(string $id): ?Comment
    {
        return $this->entityManager->getRepository(Comment::class)->find($id);
    }

    public function verifyCommentContent(string $content): void
    {
        // Check if the content is empty
        if (empty($content)) {
            throw new \Exception('The comment content is empty');
        }

        // Check if the comment content have more than 1000 characters
        if (strlen($content) > 1000) {
            throw new \Exception('The comment content have more than 1000 characters');
        }

        // Check if the comment contain only spaces
        if (trim($content) === '') {
            throw new \Exception('The comment content contain only spaces');
        }

        // Check if the comment contain bad words
        $badWordsList = [
            'gros-mots-example-1',
        ];

        foreach (explode(' ', $content) as $word) {
            if(in_array(strtolower($word), $badWordsList)) {
                throw new \Exception('The comment content contain bad words');
            }
        }
    }

    /**
     * Create a new comment
     *
     * @param string $content
     * @param Resource $resource
     * @param User $user
     * @return Comment|null
     */
    public function createComment(string $content, Resource $resource, User $user): ?Comment
    {
        // Check if the user is banned
        if ($user->isIsBanned()) {
            throw new \Exception('This user account is banned');
        }

        // Check if the comment content is valid
        $this->verifyCommentContent($content);


        $comment = (new Comment())
            ->setComment($content)
            ->setCreatedAt(new \DateTimeImmutable())
            ->setResource($resource)
            ->setUser($user);

        $this->entityManager->persist($comment);
        $this->entityManager->flush();

        return $comment;
    }

}