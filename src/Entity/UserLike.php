<?php

namespace App\Entity;

use App\Repository\UserLikeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
class UserLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'resourceLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userLikes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Resource $resource = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $likeAt = null;

    public function getId(): ?int
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
