<?php

namespace App\Service;

use App\Entity\Attachment;
use App\Entity\Resource;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class AttachmentManager
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * Create a new attachment
     *
     * @param User $user
     * @param File $file
     * @param string $filePath
     * @param string $fileUrl
     * @param string $fileName
     * @param string $type
     * @param boolean $isDeleted
     * @return Attachment
     */
    public function createNewAttachment(User $user, Resource $resource, File $file, string $filePath, string $fileUrl, string $fileName, string $type, bool $isDeleted = false): Attachment
    {
        $attachment = (new Attachment())
            ->setUser($user)
            ->setResource($resource)
            ->setFile($file)
            ->setFilePath($filePath)
            ->setFileUrl($fileUrl)
            ->setFileName($fileName)
            ->setType($type)
            ->setIsDeleted($isDeleted);

        $this->entityManager->persist($attachment);
        $this->entityManager->flush();

        return $attachment;
    }
}