<?php

namespace App\Controller\Admin;

use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TelephoneField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserCrudController extends AbstractCrudController
{
    public function __construct(
        public UserPasswordHasherInterface $userPasswordHasher
    ) {
    }

    public static function getEntityFqcn(): string
    {
        return User::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInPlural('Utilisateurs')
            ->setEntityLabelInSingular('Utilisateur')
            ->setPageTitle('index', 'Administration des utilisateurs')
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureActions(Actions $actions): Actions
    {

        return $actions
            ->add(Crud::PAGE_INDEX, Action::DETAIL)
            ->disable(Action::NEW);
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')
                ->onlyOnIndex(),
            EmailField::new('email'),
            TextField::new('pseudo'),
            ArrayField::new('roles'),
            TextField::new('firstName', 'Prénom'),
            TextField::new('lastName', 'Nom'),
            DateField::new('birthDate', 'Date de naissance')
                ->setFormat('dd.MM.yyyy'),
            TextField::new('adress', 'Adresse')
                ->hideOnIndex(),
            TextField::new('zipCode', 'Code Postal')
                ->hideOnIndex(),
            TextField::new('city', 'Ville'),
            TelephoneField::new('tel', 'Téléhpone'),
            DateTimeField::new('createdAt', 'Date de création')
                ->onlyOnIndex()
                ->setFormat("'le 'dd.MM.yyyy ' à '  HH'h'mm:ss")
                ->setTimezone('Europe/Paris'),
            BooleanField::new('isVerified', 'Email vérifié')
                ->OnlyOnIndex(),
            BooleanField::new('ban', 'Bannir')
                ->OnlyOnIndex(),
        ];
    }

}
