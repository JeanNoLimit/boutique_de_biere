<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function createEntity(string $entityFqcn)
    {
        $product = new Product();

        return $product;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            TextField::new('designation', 'Designation'),
            NumberField::new('volume', 'Volume (cl)')
                ->setNumDecimals(1),
            SlugField::new('slug', 'Slug')
                ->setTargetFieldName(['designation','volume']),
            AssociationField::new('beerTypes', 'Type de bière'),
            AssociationField::new('provider', 'Brasserie'),
            TextareaField::new('description', 'description du produit')
                ->hideOnIndex()
                ->setNumOfRows(5),
            MoneyField::new('price', 'prix T.T.C')
                ->setCurrency('EUR')
                ->setNumDecimals(2),
            IntegerField::new('quantity', 'Unité de vente'),
            IntegerField::new('stock', 'Stock'),
            Associationfield::new('productionType', 'Type de production'),
            BooleanField::new('available', 'Disponible à la vente'),
            TextareaField::new('ingredients', 'Liste des ingrédients')
                ->hideOnIndex(),
            NumberField::new('alcoholLevel', 'Taux d\'alcool')
                ->setNumDecimals(1)
                ->hideOnIndex(),
            Numberfield::new('bitterness', 'Amertume de la bière (I.B.U)')
                ->setNumDecimals(1)
                ->hideOnIndex(),
            DateTimeField::new('createdAt', 'date de création')
                ->onlyOnIndex()
                ->setFormat('dd.MM.YYYY à HH:mm:ss')
                ->setTimezone('Europe/Paris'),
            ImageField::new('imagefile', 'Image')
                ->setUploadDir('public/upload/images/products/')
                ->setBasePath('upload/images/products/')
                ->setUploadedFileNamePattern('[slug].[extension]'),
        ];
    }
}
