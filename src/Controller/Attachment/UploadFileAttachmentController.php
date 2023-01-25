<?php 

namespace App\Controller\Attachment;

use App\Entity\Attachment;
use App\Entity\Resource;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UploadFileAttachmentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(Request $request)
    {
        $file = $request->files->get('file');

        // get the last resource created
        $resource = $this->em->getRepository(Resource::class)->findOneBy([], ['id' => 'DESC']); // Todo : replace by the resource id
        
        $attachment = (new Attachment())
            ->setFileName($file->getClientOriginalName())
            ->setType($file->getMimeType())
            ->setFile($file)
            ->setUser($this->getUser())
            ->setResource($resource)
            ->setCreatedAt(new DateTimeImmutable());

        return $attachment;
    }
}