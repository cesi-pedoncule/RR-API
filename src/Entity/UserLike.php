<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Controller\UserLike\PostUserLikeController;
use App\Repository\UserLikeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6 as Uuid;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['user_like:read']],
    denormalizationContext: ['groups' => ['user_like:write']],
    operations: [
        new Get(
            name: 'get_user_like',
            normalizationContext: ['groups' => ['user_like:read']],
        ),
        new GetCollection(
            name: 'get_user_likes',
            normalizationContext: ['groups' => ['user_like:read']],
        ),
        new Post(
            name: 'post_user_like',
            denormalizationContext: ['groups' => ['user_like:write']],
            controller: PostUserLikeController::class,
        ),
        new Delete(
            name: 'delete_user_like',
            security: 'is_granted("ROLE_USER") and object.getUser() == user',
        ),
    ]
)]
class UserLike
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user_like:read', 'user:me', 'user:read', 'resource:read'])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'resourceLikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_like:read', 'user_like:write', 'resource:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_like:read', 'user_like:write', 'user:me', 'user:read'])]
    private ?Resource $resource = null;

    #[ORM\Column]
    #[Groups(['user_like:read', 'user_like:write', 'user:me', 'user:read'])]
    private ?\DateTimeImmutable $likeAt = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->likeAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
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

    public function getLikeAt(): ?\DateTimeImmutable
    {
        return $this->likeAt;
    }

    public function setLikeAt(\DateTimeImmutable $likeAt): self
    {
        $this->likeAt = $likeAt;

        return $this;
    }
}
