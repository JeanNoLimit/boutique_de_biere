<?php

namespace App\Controller\Admin;

use App\Entity\Provider;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProviderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Provider::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Brasserie')
            ->setEntityLabelInPlural('Brasseries');

    }

    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm()->onlyOnIndex(),
            TextField::new('name', 'Brasserie'),
            TextField::new('adress', 'Adresse'),
            TextField::new('cp','Code Postal'),
            TextField::new('city', 'Ville'),
            UrlField::new('webSite', 'Site Web'),
            UrlField::new('socialNetwork', 'Réseaux social')
            
        ];
    }
    
}
