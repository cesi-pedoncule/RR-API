<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6 as Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Controller\Comment\PostCommentController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['comment:read']],
    denormalizationContext: ['groups' => ['comment:write']],
    operations: [
        new Get(
            name: 'get_comment',
            uriTemplate: '/comments/{id}'
        ),
        new GetCollection(
            name: 'get_comments',
            uriTemplate: '/comments'
        ),
        new Post(
            name: 'post_comment',
            uriTemplate: '/comments',
            controller: PostCommentController::class,
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can create comments.',
        ),
        new Put(
            name: 'put_comment',
            uriTemplate: '/comments/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can edit other users comments.',
            denormalizationContext: ['groups' => ['comment:put']],
        ),
        new Delete(
            name: 'delete_comment',
            uriTemplate: '/comments/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can delete other users comments.',
        )
    ],
)]
#[ORM\HasLifecycleCallbacks]
class Comment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['resource:read', 'comment:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['resource:read', 'comment:read', 'comment:write', 'comment:put'])]
    private ?string $comment = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['resource:read', 'comment:read', 'comment:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[Groups(['comment:read', 'comment:write'])]
    private ?Resource $resource = null;

    #[ORM\Column]
    #[Groups(['resource:read', 'comment:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getResource(): ?Resource
    {
        return $this->resource;
    }

    public function setResource(?Resource $resource): self
    {
        $this->resource = $resource;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
