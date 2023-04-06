<?php 

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Resource;
use App\Entity\ValidationState;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class ResourceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // Create 20 fake resources
        for ($i=0; $i < 25; $i++) {

            $validationState = (new ValidationState())
                ->setState($this->getReference('state_validated'))
                ->setModerator($this->getReference('user_0'));

            $manager->persist($validationState);

            $resource = (new Resource())
                ->setTitle($faker->sentence(3))
                ->setDescription($faker->paragraph(3))
                ->addCategory($this->getReference('category_' . rand(0, 4)))
                ->setIsPublic($i >= 20 ? false : true)
                ->addValidationState($validationState)
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