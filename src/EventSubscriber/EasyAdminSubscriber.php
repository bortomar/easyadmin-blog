<?php
namespace App\EventSubscriber;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityPersistedEvent::class => ['setPostAuthor'],
        ];
    }

    public function setPostAuthor(BeforeEntityPersistedEvent $event)
    {
        $entity = $event->getEntityInstance();
        if (!($entity instanceof Post)) {
            return;
        }
        $entity->setAuthor($this->user);   
    }
}