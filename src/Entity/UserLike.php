<?php

namespace App\Entity;

use App\Repository\UserLikeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserLikeRepository::class)]
class UserLike
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'ResourceLikes')]
    private Collection $user;

    #[ORM\ManyToMany(targetEntity: Resource::class, inversedBy: 'userLikes')]
    private Collection $resource;

    #[ORM\Column]
    private ?\DateTimeImmutable $likeAt = null;

    #[ORM\PrePersist]
    public function setLikeAtValue()
    {
        $this->likeAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->user = new ArrayCollection();
        $this->resource = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUser(): Collection
    {
        return $this->user;
    }

    public function addUser(User $user): self
    {
        if (!$this->user->contains($user)) {
            $this->user->add($user);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        $this->user->removeElement($user);

        return $this;
    }

    /**
     * @return Collection<int, Resource>
     */
    public function getResource(): Collection
    {
        return $this->resource;
    }

    public function addResource(Resource $resource): self
    {
        if (!$this->resource->contains($resource)) {
            $this->resource->add($resource);
        }

        return $this;
    }

    public function removeResource(Resource $resource): self
    {
        $this->resource->removeElement($resource);

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
