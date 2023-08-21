<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
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
        $product->setCreatedAt(new \DatetimeImmutable());

        return $product;

    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('designation', 'Designation'),
            NumberField::new('volume', 'Volume (cl)')
                ->setNumDecimals(1),
            SlugField::new('slug', 'Slug')->setTargetFieldName(['designation','volume']), 
            TextEditorField::new('description', 'description du produit')->hideOnIndex(),
            MoneyField::new('price', 'prix T.T.C')
                ->setCurrency('EUR')
                ->setNumDecimals(2),
            IntegerField::new('quantity', 'Unité de vente'),
            IntegerField::new('stock', 'Stock'),
            BooleanField::new('available', 'Disponible à la vente'),
            TextEditorField::new('ingredients', 'Liste des ingrédients'),
            NumberField::new('alcoholLevel', 'Taux d\'alcool')->setNumDecimals(1),
            Numberfield::new('bitterness', 'Amertume de la bière (I.B.U)')->setNumDecimals(1),
            Associationfield::new('productionType', 'Type de production'),
            AssociationField::new('beerTypes', 'Type de bière'),
            AssociationField::new('provider', 'Brasserie'), 
            DateTimeField::new('createdAt', 'date de création')->onlyOnIndex(),
        ];
    }
    
}
