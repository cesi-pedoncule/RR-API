<?php 

namespace App\DataFixtures;
use App\DataFixtures\UserFixtures;
use App\Entity\Comment;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = \Faker\Factory::create('fr_FR');
        // Create 20 fake resources
        for ($i=0; $i < 20; $i++) { 
            $comment = (new Comment())
                ->setComment($faker->sentence(3))
                ->setResource($this->getReference('resource_' . rand(0, 19)))
                ->setUser($this->getReference('user_' . rand(0, 9)));
            
            $manager->persist($comment);
            $this->addReference('comment_' . $i, $comment);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ResourceFixtures::class,
        ];
    }
}