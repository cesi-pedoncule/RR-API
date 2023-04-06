<?php 

namespace App\Controller\Attachment;

use App\Entity\Attachment;
use App\Entity\Resource;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class UploadFileAttachmentController extends AbstractController
{

    public function __construct(private EntityManagerInterface $em)
    {
    }

    public function __invoke(Request $request): Attachment|JsonResponse
    {
        // Check if the resource is send in the request
        if (!$request->get('resource')) {
            return new JsonResponse(['error' => 'Resource is required => send [resource => $id]'], 400);
        }

        // Check if the user is logged in
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Get the file
        $file = $request->files->get('file');
        if (!$file) {
            return new JsonResponse(['error' => 'File is required'], 400);
        }

        // Get the resource send in the request
        $resource = $this->em->getRepository(Resource::class)->find($request->get('resource'));
        
        $attachment = (new Attachment())
            ->setFileName($file->getClientOriginalName())
            ->setType($file->getMimeType())
            ->setFile($file)
            ->setUser($this->getUser())
            ->setResource($resource)
            ->setCreatedAt(new DateTimeImmutable());
            
        $this->em->persist($attachment);
        
        $attachment->setFileUrl("https://api.ressourcesrelationnelles.social/attachments/{$attachment->getFilePath()}");

        $this->em->persist($attachment);
        $this->em->flush();

        return $attachment;
    }
}