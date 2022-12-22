<?php

namespace App\Entity;

use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\UuidV6 as Uuid;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Controller\Attachment\UploadFileAttachmentController;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @Vich\Uploadable
 */
#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
#[ApiResource(
    formats: ['json'], 
    normalizationContext: ['groups' => ['attachment:read']],
    denormalizationContext: ['groups' => ['attachment:write']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            name: 'post_attachment',
            uriTemplate: '/attachments/resource',
            controller: UploadFileAttachmentController::class,
            inputFormats: ['multipart' => ['multipart/form-data']],
            deserialize: false,
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object', 
                                'properties' => [
                                    'file' => [
                                        'type' => 'string', 
                                        'format' => 'binary'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            security: 'is_granted("ROLE_USER")',
            securityMessage: 'Only authenticated users can create attachments.',
        ),
        new Put(
            name: 'put_attachment',
            uriTemplate: '/attachments/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can edit other users attachments.',
            denormalizationContext: ['groups' => ['attachment:put']],
        ),
        new Delete(
            name: 'delete_attachment',
            uriTemplate: '/attachments/{id}',
            security: 'is_granted("ROLE_ADMIN") or object.getUser() == user',
            securityMessage: 'Only admins can delete other users attachments.',
        )
    ]
)]
#[ORM\HasLifecycleCallbacks]
class Attachment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    #[Groups(['attachment:read', 'resource:read'])]
    private ?Uuid $id = null;

    #[Vich\UploadableField(mapping: "attachment_file", fileNameProperty: "filePath")]
    private ?File $file = null;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $filePath = null;

    #[Groups(['attachment:read', 'resource:read'])]
    private ?string $fileUrl = null;

    #[ORM\Column(length: 255)]
    #[Groups(['attachment:read', 'attachment:write', 'attachment:put', 'resource:read'])]
    private ?string $fileName = null;

    #[ORM\Column(length: 255)]
    #[Groups(['attachment:read', 'resource:read'])]
    private ?string $type = null;

    #[ORM\Column]
    #[Groups(['attachment:read', 'resource:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['attachment:read', 'resource:read'])]
    private ?bool $isDeleted = null;

    #[ORM\ManyToOne(inversedBy: 'attachments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['attachment:read'])]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'Attachments')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['attachment:read', 'attachment:write'])]
    private ?Resource $resource = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(?string $filePath): self
    {
        $this->filePath = $filePath;

        return $this;
    }

    public function getFileUrl(): ?string
    {
        return $this->fileUrl;
    }

    public function setFileUrl(?string $fileUrl): self
    {
        $this->fileUrl = $fileUrl;

        return $this;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }

    public function setFileName(string $fileName): self
    {
        $this->fileName = $fileName;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

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

    public function isIsDeleted(): ?bool
    {
        return $this->isDeleted;
    }

    public function setIsDeleted(bool $isDeleted): self
    {
        $this->isDeleted = $isDeleted;

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
}
