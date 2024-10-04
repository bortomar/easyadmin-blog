<?php

namespace App\DataFixtures;

use App\DataFixtures\Helpers\Randomizer;
use App\Entity\Post;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class PostFixtures extends Fixture implements DependentFixtureInterface
{

    const CONTENT = '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus vel iaculis velit. '.
                    'Vivamus sit amet accumsan purus. Proin gravida mi ligula, at faucibus diam malesuada vitae. '.
                    'Proin cursus dui augue, a fermentum arcu porttitor ut. Integer nec luctus augue, sit amet '.
                    'condimentum urna. Donec pretium diam non semper tincidunt. Aliquam vitae volutpat tortor. '.
                    'Pellentesque sed tellus suscipit, sagittis ipsum feugiat, suscipit metus.</p>'.
                    '<p>Morbi odio magna, porta in nisl rhoncus, molestie vulputate velit. In lobortis risus nunc, '.
                    'non molestie est lacinia nec. Morbi tortor ipsum, condimentum vitae orci at, sollicitudin hendrerit nisi. '.
                    'Aliquam efficitur auctor orci. Sed in consequat orci, eu finibus libero. Integer sodales maximus nibh, nec '.
                    'dictum sapien auctor vitae. Praesent nisl augue, accumsan vitae dui a, rutrum pellentesque ligula. Nullam eu '.
                    'diam eget lorem maximus sagittis.</p>';
   public function getDependencies()
   {
       return [
           UserFixtures::class,
       ];
   }

    public function load(ObjectManager $manager): void
    {
     
        $users = $manager->getRepository(User::class)->findAll();

        
        for ($i = 1; $i < 30; $i++) {
            $author = $users[array_rand($users)];    
            $created = Randomizer::getDateTime(new \DateTime('2021-1-1'));
            $update = Randomizer::getDateTime(new \DateTime('2021-1-1'));
            
            $manager->persist(
                (new Post())
                    ->setAuthor($author)
                    ->setCreatedAt($created)
                    ->setUpdatedAt($update)
                    ->setContent(self::CONTENT)
                    ->setTitle('Post #'. $i)
            );
        }

        $manager->flush();
    }
}
