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
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ResourceRepository::class)]
#[ApiResource(
    formats: ['json'], 
    normalizationContext: ['groups' => ['resource:read']],
    denormalizationContext: ['groups' => ['resource:write']],
    operations: [
        new Get(
            normalizationContext: ['groups' => ['resource:read']],
            denormalizationContext: ['groups' => ['resource:write']],
            name: 'get_resource',
            uriTemplate: '/resources/{id}'
        ),
        new GetCollection(
            normalizationContext: ['groups' => ['resource:read']],
            denormalizationContext: ['groups' => ['resource:write']],
            name: 'get_resources',
            uriTemplate: '/resources'
        ),
        new Post(
            denormalizationContext: ['groups' => ['resource:write']],
            normalizationContext: ['groups' => ['resource:read']],
            name: 'post_resource',
            uriTemplate: '/resources',
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can create resources.',
        ),
        new Put(
            denormalizationContext: ['groups' => ['resource:write']],
            normalizationContext: ['groups' => ['resource:read']],
            name: 'put_resource',
            uriTemplate: '/resources/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getCreator() == user',
            securityMessage: 'Only admins can edit other users resources.',
        ),
        new Delete(
            name: 'delete_resource',
            uriTemplate: '/resources/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getCreator() == user',
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
    #[Groups(['resource:read', 'user:read', 'attachment:read', 'category:read'])]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['resource:read', 'resource:write', 'user:read', 'attachment:read', 'category:read'])]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['resource:read', 'resource:write', 'attachment:read', 'category:read'])]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Attachment::class)]
    #[Groups(['resource:read', 'resource:write'])]
    private Collection $attachments;

    #[ORM\Column]
    #[Groups(['resource:read', 'attachment:read', 'category:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['resource:read', 'attachment:read', 'category:read'])]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\ManyToOne(inversedBy: 'resources')]
    #[Groups(['resource:read', 'attachment:read', 'category:read'])]
    private ?User $user = null;

    #[ORM\Column(options: ['default' => true])]
    #[Groups(['resource:read', 'resource:write', 'attachment:read', 'category:read'])]
    private ?bool $isPublic = null;

    #[ORM\Column(options: ['default' => false])]
    #[Groups(['resource:read', 'attachment:read', 'category:read'])]
    private ?bool $isDeleted = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'resources')]
    #[Groups(['resource:read', 'resource:write'])]
    private Collection $categories;

    #[ORM\OneToMany(mappedBy: 'resource', targetEntity: Comment::class)]
    #[Groups(['resource:read'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'validationState', targetEntity: ValidationState::class)]
    #[Groups(['resource:read', 'resource:write', 'category:read'])]
    private Collection $validationStates;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
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
}
