<?php

namespace App\Form;

use App\Model\Filters;
use App\Entity\BeerType;
use App\Entity\Provider;
use Doctrine\ORM\QueryBuilder;
use App\Repository\BeerTypeRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class FiltersType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('searchProduct', SearchType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'searchProducts',
                    'maxlength' => '30',
                    'error_bubbling' => true,
                ],
                'sanitize_html' => true,
            ])
            ->add('providers', EntityType::class, [
               'class' => Provider::class,
               'label' => false,
               'choice_label' => 'name',
               'expanded' => true,
               'multiple' => true,
               'required' => false,
               'attr' => [
                    'class' => 'filters_checkbox'
               ],
               'error_bubbling' => true,

            ])
            ->add('beerTypes', EntityType::class, [
                'class' => BeerType::class,
                'query_builder' => function (BeerTypeRepository $er): QueryBuilder {
                    return $er->createQueryBuilder('bt')
                        ->join('bt.products', 'p')
                        ->andWhere('p.available = true')
                        ->groupBy('bt.id')
                        ->orderBy('bt.name');
                },
                'expanded' => true,
                'multiple' => true,
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
            ])
            ->add('min', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'label' => false,
                'divisor' => 100,
                'error_bubbling' => true,
            ])
            ->add('max', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'label' => false,
                'divisor' => 100,
                'error_bubbling' => true,
            ])
            ->add('tauxMin', NumberType::class, [
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
            ])
            ->add('tauxMax', NumberType::class, [
                'required' => false,
                'label' => false,
                'error_bubbling' => true,
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => "button_base button_validation"],
                'label' => 'filtrer les résultats'
            ])

            // Ajout des paramètres qui ne sont pas présents dans le form
            ->add('page', IntegerType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none'],
                'error_bubbling' => true,
            ])

            ->add('sort', TextType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none'],
                'error_bubbling' => true,
            ])

            ->add('direction', TextType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none'],
                'error_bubbling' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filters::class,
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
    }

    //Sans l'ajout de cette fonction le résultat du formulaire serait contenu dans un tableau filters (le nom du modèle).
    //On remplace cela par une chaine de caractère vide pour ne pas surcharger l'URL.
    public function getBlockPrefix(): string
    {
        return '';
    }
}
