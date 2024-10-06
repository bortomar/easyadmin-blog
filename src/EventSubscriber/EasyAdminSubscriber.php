<?php
namespace App\EventSubscriber;

use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeCrudActionEvent;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityPersistedEvent;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
            BeforeCrudActionEvent::class => ['setPostPermissions'],
        ];
    }

    public function setPostPermissions(BeforeCrudActionEvent $event){
        $context = $event->getAdminContext();
        $actions = $event->getAdminContext()->getCrud()->getActionsConfig();
        $action = $context->getCrud()->getCurrentAction();

        $author = $context->getEntity()?->getInstance()?->getAuthor();
        $isAdmin = array_search('ROLE_ADMIN', $this->user?->getRoles() ?? []) !== false;

        if (!$isAdmin && $this->user !== $author) {
            if ($action == Crud::PAGE_EDIT) {
                $event->setResponse(new RedirectResponse('/'));
            }

            $actions->removeAction(Crud::PAGE_DETAIL, Action::DELETE);
            $actions->removeAction(Crud::PAGE_DETAIL, Action::EDIT);
        }
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