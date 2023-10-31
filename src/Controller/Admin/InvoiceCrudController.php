<?php

namespace App\Controller\Admin;

use App\Entity\Invoice;
use Vich\UploaderBundle\Form\Type\VichFileType;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;



class InvoiceCrudController extends AbstractCrudController
{



    public static function getEntityFqcn(): string
    {
        return Invoice::class;
    }

    public function configureFields(string $pageName): iterable
    {

        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('reference'),
            textField::new('file')
                ->setFormType(VichFileType::class, [
                ])->onlyOnForms(),
            TextField::new('fileName', 'telecharger PDF')->setTemplatePath('admin/fields/document_link.html.twig')->onlyOnIndex(),
            DateTimeField::new('createdAt', 'date de création')
            ->onlyOnIndex()
            ->setFormat('dd.MM.YYYY à HH:mm:ss')
            ->setTimezone('Europe/Paris'),
        ];
    }
    
}
