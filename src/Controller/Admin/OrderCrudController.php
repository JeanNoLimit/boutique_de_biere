<?php

namespace App\Controller\Admin;

use App\Entity\Order;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Commandes')
            ->setEntityLabelInSingular('Commande')
            ->setPageTitle('index', 'Administration des commandes');
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
            ->hideOnform(),
            TextField::new('reference', 'Réference'),
            BooleanField::new('isPaid', 'Commande payée'),
            booleanField::new('isProcessed', 'Commande Préparée'),
            AssociationField::new('user', 'Utilisateur'),
            CollectionField::new('orderDetails', 'Détails de la commande')
                ->useEntryCrudForm(OrderDetailsCrudController::class)
                ->renderExpanded(),
            DateTimeField::new('createdAt', 'date de création')
            ->hideOnForm()
            ->setFormat('dd.MM.YYYY à HH:mm:ss')
            ->setTimezone('Europe/Paris'),

        ];
    }
}
