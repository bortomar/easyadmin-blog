<?php

namespace App\DataFixtures;

use App\DataFixtures\Helpers\Randomizer;
use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CommentFixtures extends Fixture implements DependentFixtureInterface
{   const CONTENT = '<p>Morbi odio magna, porta in nisl rhoncus, molestie vulputate velit. In lobortis risus nunc, '.
                    'non molestie est lacinia nec. Morbi tortor ipsum, condimentum vitae orci at, sollicitudin hendrerit nisi. '.
                    'Aliquam efficitur auctor orci. Sed in consequat orci, eu finibus libero. Integer sodales maximus nibh, nec '.
                    'dictum sapien auctor vitae. Praesent nisl augue, accumsan vitae dui a, rutrum pellentesque ligula. Nullam eu '.
                    'diam eget lorem maximus sagittis.</p>';

    
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            PostFixtures::class,
        ];
    }

    public function load(ObjectManager $manager): void
    {
    
        $posts = $manager->getRepository(Post::class)->findAll();
        
        for ($i = 1; $i < 100; $i++) {
            $post = $posts[array_rand($posts)];    
            $date = Randomizer::getDateTime($post->getCreatedAt());
            
            $manager->persist(
                (new Comment())
                    ->setAuthor(Randomizer::getName())
                    ->setCreatedAt($date)
                    ->setUpdatedAt($date)
                    ->setContent(self::CONTENT)
                    ->setPost($post)
            );
        }

        $manager->flush();
    }

}
