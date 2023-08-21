<?php

namespace App\Controller\Admin;

use App\Entity\ProductionType;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductionTypeCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProductionType::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Type de production')
            ->setEntityLabelInPlural('Types de production');

    }

    public function configureFields(string $pageName): iterable
    {
        return [
            TextField::new('productionType', 'type de production')
        ];
    }

}
