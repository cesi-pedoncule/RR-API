<?php 

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // Create 5 fake categories
        for ($i=0; $i < 25; $i++) { 
            $category = (new Category())
                ->setName($faker->sentence(3))
                ->setIsVisible($i >= 20 ? false : true)
                ->setCreator($this->getReference('user_' . rand(0, 9)));
            
                $manager->persist($category);
                $this->addReference('category_' . $i, $category);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}