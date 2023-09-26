<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ReviewCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Review::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Modération du commentaire')
            ->setEntityLabelInPlural('Modération des commentaires')
            ->setDateTimeFormat('dd.MM.YYYY à HH:mm:ss')
            ->setTimezone('Europe/Paris')
            ->setSearchFields(['product.designation', 'user.pseudo', 'description', 'rating', 'createdAt'])
            ->setDefaultSort(['updatedAt' => 'DESC','createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
            ->disable(Action::NEW)
            // /!\ Fonction Edit à supprimer avant présentation! /!\
            // ->disable(Action::EDIT)
            ->update(Crud::PAGE_INDEX, Action::EDIT, function (Action $action) {
                return $action->setLabel("Modifier");
            })
            ->update(Crud::PAGE_INDEX, Action::DELETE, function (Action $action) {
                return $action->setIcon('fa-solid fa-trash style="color: #ff0000;"')->setLabel("Supprimer");
            })
        ;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->OnlyOnIndex(),
            AssociationField::new('user', 'pseudo')
                ->formatValue(function ($value, $entity) {
                    $user = $entity->getUser();
                    $value = $user->getPseudo();
                    return $value;
                }),
            AssociationField::new('product', 'Désignation du produit'),
            IntegerField::new('rating', 'note'),
            TextareaField::new('description')
                ->renderAsHtml(),
            DateTimeField::new('createdAt', 'Date de création')
                ->OnlyOnIndex(),
            DateTimeField::new('updatedAt', 'Date de Mise à jour')
                ->OnlyOnIndex(),
        ];
    }
}
