<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use App\Repository\UserFollowRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\UuidV6 as Uuid;

#[ORM\Entity(repositoryClass: UserFollowRepository::class)]
#[ORM\HasLifecycleCallbacks]
#[ApiResource(
    formats: ['json'],
    normalizationContext: ['groups' => ['user_follow:read']],
    denormalizationContext: ['groups' => ['user_follow:write']],
    operations: [
        new Get(
            name: 'get_user_follow',
            normalizationContext: ['groups' => ['user_follow:read']],
        ),
        new GetCollection(
            name: 'get_user_follows',
            normalizationContext: ['groups' => ['user_follow:read']],
        ),
        new Post(
            name: 'post_user_follow',
            denormalizationContext: ['groups' => ['user_follow:write']],
        ),
        new Delete(
            name: 'delete_user_follow',
            security: 'is_granted("ROLE_USER") and object.getFollower() == user',
        )
    ]
)]
class UserFollow
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['user_follow:read', 'user:me', 'user:read'])]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'userFollows')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_follow:read', 'user_follow:write'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userFollowers')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['user_follow:read', 'user_follow:write'])]
    private ?user $follower = null;

    #[ORM\Column]
    #[Groups(['user_follow:read', 'user:me', 'user:read'])]
    private ?\DateTimeImmutable $followAt = null;

    #[ORM\PrePersist]
    public function prePersist(): void
    {
        $this->followAt = new \DateTimeImmutable();
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

    public function getFollower(): ?user
    {
        return $this->follower;
    }

    public function setFollower(?user $follower): self
    {
        $this->follower = $follower;

        return $this;
    }

    public function getFollowAt(): ?\DateTimeImmutable
    {
        return $this->followAt;
    }

    public function setFollowAt(\DateTimeImmutable $followAt): self
    {
        $this->followAt = $followAt;

        return $this;
    }
}
