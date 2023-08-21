<?php

namespace App\Controller\Admin;

use App\Entity\BeerType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class BeerTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return BeerType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Types de bière')
            ->setEntityLabelInPlural('Type de bière');

    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('name', 'Nom'),
        ];
    }
    
}
