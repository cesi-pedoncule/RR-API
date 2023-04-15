<?php

namespace App\DataFixtures;

use App\Entity\Attachment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\File\File;

class AttachmentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $attachment = (new Attachment())
            ->setFile(new File('tests/TestsFiles/test.txt'))
            ->setFileUrl('http://localhost/tests/TestsFiles/test.txt')
            ->setFileName('test.txt')
            ->setType('text/plain')
            ->setUser($this->getReference('user_0'))
            ->setResource($this->getReference('resource_0'));
        
            $manager->persist($attachment);
        $manager->flush();
        $this->addReference('attachment_0', $attachment);
    }

    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            ResourceFixtures::class,
        ];
    }
}