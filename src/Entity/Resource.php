<?php

namespace App\Entity;

use App\Repository\ResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6 as Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\Resource\DeleteResourceController;
use App\Controller\Resource\GetCollectionResourceController;
use App\Controller\Resource\GetResourceController;
use App\Controller\Resource\PostResourceController;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(
    formats: ['json'], 
    normalizationContext: ['groups' => ['resource:read']],
    denormalizationContext: ['groups' => ['resource:write']],
    operations: [
        new Get(
            name: 'get_resource',
            uriTemplate: '/resources/{id}',
            controller: GetResourceController::class,
        ),
        new GetCollection(
            name: 'get_resources',
            uriTemplate: '/resources',
            controller: GetCollectionResourceController::class,
        ),
        new Post(
            name: 'post_resource',
            uriTemplate: '/resources',
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can create resources.',
            controller: PostResourceController::class,
            denormalizationContext: ['groups' => ['resource:post']],
        ),
        new Put(
            name: 'put_resource',
            uriTemplate: '/resources/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can edit other users resources.',
            denormalizationContext: ['groups' => ['resource:put']],
        ),
        new Delete(
            name: 'delete_resource',
            controller: DeleteResourceController::class,
            uriTemplate: '/resources/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can delete other users resources.',
        )
    ],
)]
#[ORM\HasLifecycleCallbacks]
class Resource
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['resource:read', 'user:read', 'user:me', 'attachment:read', 'category:read', 'comment:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:read', 'resource:write', 'resource:post', 'resource:put', 'user:read', 'user:me', 'attachment:read', 'category:read', 'comment:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['resource:read', 'resource:write', 'resource:put', 'resource:post', 'attachment:read', 'category:read', 'comment:read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Attachment::class)]
    #[Groups(['resource:read', 'resource:write', 'resource:post'])]
    private Collection $attachments;

    #[ORM\Column]
    #[Groups(['resource:read', 'attachment:read', 'category:read', 'comment:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['resource:read', 'attachment:read', 'category:read', 'comment:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[Groups(['resource:read', 'attachment:read', 'category:read', 'comment:read'])]
    private ?User $user = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['resource:read', 'resource:write', 'resource:post', 'resource:put', 'attachment:read', 'category:read', 'comment:read'])]
    private ?bool $isPublic = null;

    #[ORM\Column(options: ['default' => false])]
    private ?bool $isDeleted = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'resources')]
    #[Groups(['resource:read', 'resource:write', 'resource:put', 'resource:post'])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Comment::class)]
    #[Groups(['resource:read'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: ValidationState::class)]
    #[Groups(['resource:read', 'resource:write'])]
    private Collection $validationStates;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: UserLike::class)]
    private Collection $userLikes;

    #[ORM\PrePersist]
    public function setCreationValues()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->setIsDeleted(false);
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue()
    {
        $this->updatedAt = new \DateTimeImmutable();
    }

    public function __construct()
    {
        $this->attachments = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->categories = new ArrayCollection();
        $this->validationStates = new ArrayCollection();
        $this->userLikes = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): self
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setResource($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): self
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getResource() === $this) {
                $attachment->setResource(null);
            }
        }

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

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

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): self
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setResource($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getResource() === $this) {
                $comment->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ValidationState>
     */
    public function getValidationStates(): Collection
    {
        return $this->validationStates;
    }

    public function addValidationState(ValidationState $validationState): self
    {
        if (!$this->validationStates->contains($validationState)) {
            $this->validationStates->add($validationState);
            $validationState->setResource($this);
        }

        return $this;
    }

    public function removeValidationState(ValidationState $validationState): self
    {
        if ($this->validationStates->removeElement($validationState)) {
            // set the owning side to null (unless already changed)
            if ($validationState->getResource() === $this) {
                $validationState->setResource(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, UserLike>
     */
    public function getUserLikes(): Collection
    {
        return $this->userLikes;
    }

    public function addUserLike(UserLike $userLike): self
    {
        if (!$this->userLikes->contains($userLike)) {
            $this->userLikes->add($userLike);
            $userLike->setResource($this);
        }

        return $this;
    }

    public function removeUserLike(UserLike $userLike): self
    {
        if ($this->userLikes->removeElement($userLike)) {
            // set the owning side to null (unless already changed)
            if ($userLike->getResource() === $this) {
                $userLike->setResource(null);
            }
        }

        return $this;
    }
}
