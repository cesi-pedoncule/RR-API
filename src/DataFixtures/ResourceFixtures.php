<?php 

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Resource;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // Create 20 fake resources
        for ($i=0; $i < 20; $i++) { 
            $resource = (new Resource())
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph(3))
                ->addCategory($this->getReference('category_' . rand(0, 4)))
                ->setIsPublic((bool)rand(0, 1))
                ->setIsDeleted(false)
                ->addValidationState($this->getReference('validationState_' . rand(1, 3)))
                ->setUser($this->getReference('user_' . rand(0, 9)));
            $manager->persist($resource);
            $this->addReference('resource_' . $i, $resource);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            CategoryFixtures::class,
            ValidationStateFixtures::class,
        ];
    }
}