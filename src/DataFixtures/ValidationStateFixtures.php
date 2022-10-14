<?php 

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Resource;
use App\Entity\ValidationState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ValidationStateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Create validations states
        $validationState1 = (new ValidationState())
            ->setState(1)
            ->setModerator($this->getReference('user_0'))
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($validationState1);

        // Create validations states
        $validationState2 = (new ValidationState())
            ->setState(2)
            ->setModerator($this->getReference('user_0'))
            ->setUpdatedAt(new \DateTimeImmutable());
        $manager->persist($validationState2);

        // Create validations states
        $validationState3 = (new ValidationState())
            ->setState(3)
            ->setModerator($this->getReference('user_0'))
            ->setUpdatedAt(new \DateTimeImmutable());
        
        $manager->persist($validationState3);
        $manager->flush();

        $this->addReference('validationState_1', $validationState1);
        $this->addReference('validationState_2', $validationState2);
        $this->addReference('validationState_3', $validationState3);
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}