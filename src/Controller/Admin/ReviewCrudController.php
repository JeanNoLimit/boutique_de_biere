<?php

namespace App\Controller\Admin;

use App\Entity\Review;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
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
            ->setDateFormat('short')
            ->setTimeFormat('short');
    
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            AssociationField::new('user', 'pseudo utilisteur')
                ->formatValue(function ($value, $entity) {
                    $user = $entity->getUser();
                    return $user->getPseudo();
                }),
            AssociationField::new('product', 'produit'),
            IntegerField::new('rating', 'note'),
            TextareaField::new('description')
                ->renderAsHtml(),
            DateTimeField::new('createdAt'),
            DateTimeField::new('updatedAt'),
        ];
    }

}
