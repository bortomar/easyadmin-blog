<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    
    public const USER = 'user';
    public const ADMIN = 'admin';
    public const PASSWORD = '$2y$13$Wpd8byEhtLdPNawiqfNI7Ow/9KRfE396VkWeaFwTC.InVrzpbyDqe';
    
    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setEmail('admin@gmail.com')
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword(self::PASSWORD);
        $this->setReference(self::ADMIN, $user);
        
        $manager->persist($user);
        
        $user = (new User())
            ->setEmail('user@gmail.com')
            ->setRoles(["ROLE_USER"])
            ->setPassword(self::PASSWORD);
            
        $this->setReference(self::USER, $user);
        
        $manager->persist($user);

        $manager->flush();
    }
}
