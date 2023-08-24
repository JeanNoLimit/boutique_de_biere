<?php

namespace App\Controller\Admin;

use App\Entity\ShopParameters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ShopParametersCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ShopParameters::class;
    }

    // public function configureCrud(Crud $crud): Crud
    // {
    //     return $crud
          
    // }

//    public function configureAction(Actions $actions) : Actions
//    {
//         return parent::configureActions($actions)
//         ->remove(Crud::PAGE_INDEX, Action::NEW);
//    } 
    public function configureFields(string $pageName): iterable
    {
        return [
            ImageField::new('logoFile', 'Logo du site')
                ->setUploadDir('public/upload/images/site/')
                ->setBasePath('upload/images/site/')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            ImageField::new('defaultImageProduct', 'Image produit par défaut')
                ->setUploadDir('public/upload/images/site/')
                ->setBasePath('upload/images/site/')
                ->setUploadedFileNamePattern('[randomhash].[extension]'),
            
        ];
    }
    
}
