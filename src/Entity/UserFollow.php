<?php

namespace App\Entity;

use App\Repository\UserFollowRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6 as Uuid;

#[ORM\Entity(repositoryClass: UserFollowRepository::class)]
#[ORM\HasLifecycleCallbacks]
class UserFollow
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'userFollows')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userFollowers')]
    #[ORM\JoinColumn(nullable: false)]
    private ?user $follower = null;

    #[ORM\Column]
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
