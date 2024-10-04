<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use EasyCorp\Bundle\EasyAdminBundle\Collection\FieldCollection;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud; 
use EasyCorp\Bundle\EasyAdminBundle\Config\KeyValueStore;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;  
use EasyCorp\Bundle\EasyAdminBundle\Factory\EntityFactory;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField; 
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class PostCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        
        return Post::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setTimezone('Europe/Prague')
            ->setDateTimeFormat('d. M. Y H:m')
            ->overrideTemplates([
                'crud/field/text_editor' => 'post/field_content.html.twig',
                'crud/detail' => 'post/detail.html.twig'
            ])
            ->showEntityActionsInlined();
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->remove(Crud::PAGE_DETAIL, Action::DELETE)
            ->add(Crud::PAGE_EDIT, Action::DELETE)
            ->setPermission(Action::EDIT, 'ROLE_ADMIN')
            ->setPermission(Action::DELETE, 'ROLE_ADMIN')
            ->setPermission(Action::NEW, 'ROLE_ADMIN');
    }


    public function createCommentForm() {
        $entityFactor = $this->container->get(EntityFactory::class);
        $commentEntityDto = $entityFactor->createForEntityInstance(new Comment());
        $context = $this->getContext();

        $entityFactor->processFields(
            $commentEntityDto, 
            FieldCollection::new([
                FormField::addFieldset('Comments'),
                TextField::new('author'),
                TextareaField::new('content'),
            ])
        );

        $commentForm = $this->createNewForm(
            $commentEntityDto, 
            KeyValueStore::new([]), 
            $context
        );

        $commentForm->add('Save', SubmitType::class );
        $commentForm->handleRequest($context->getRequest());


        return $commentForm;
    }

    public function detail(AdminContext $context) {
        $parent = parent::detail($context);
        $commentForm = $this->createCommentForm();

        if ($commentForm->isSubmitted() && $commentForm->isValid()) {
            $entityInstance = $commentForm->getData();
            $manager = $this->container
                ->get('doctrine')
                ->getManagerForClass(Comment::class);

            $entityInstance->setPost($context->getEntity()->getInstance());
            $this->persistEntity($manager, $entityInstance);
            return $this->redirect($context->getRequest()->getUri());
        }

        $parent->set('new_form', $commentForm);
        return $parent;

    }

    public function configureFields(string $pageName): iterable
    {

        return [
            TextField::new('title'),
            DateTimeField::new('createdAt')
                ->hideOnForm(),
            DateTimeField::new('updatedAt')
                ->hideOnForm(),
            TextField::new('author'),
            TextEditorField::new('content')
                ->onlyOnDetail(),
            TextEditorField::new('content')
                ->onlyOnForms()
                ->setTrixEditorConfig([
                    'blockAttributes' => [
                        'default' => ['tagName' => 'p'],
                        'heading1' => ['tagName' => 'h2'],
                    ],
                    'css' => [
                        'attachment' => 'admin_file_field_attachment',
                    ],
                ]),
            CollectionField::new('comments') 
                ->allowAdd(false)
                ->setEntryIsComplex()
                ->onlyOnForms()
                ->useEntryCrudForm(),
            AssociationField::new('comments')
                ->onlyOnIndex()
        ];
    }
}
