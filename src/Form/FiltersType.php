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
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class FiltersType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('searchProduct', TextType::class, [
                'required' => false,
                'label' => false,
                'attr' => [
                    'placeholder' => 'Rechercher',
                    'class' => 'searchProducts'
                ]
            ])
            ->add('providers', EntityType::class, [
               'class' => Provider::class,
               'label' => false,
               'choice_label' => 'name',
               'expanded' => true,
               'multiple' => true,
               'required' => false,
               'attr' => ['class' => 'filters_checkbox']
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
                'label' => false
            ])
            ->add('min', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'label' => false,
                'divisor' => 100,
                'attr' => ['min' => '0', 'max' => '100'],
            ])
            ->add('max', MoneyType::class, [
                'required' => false,
                'currency' => false,
                'label' => false,
                'divisor' => 100,
                'attr' => ['min' => '0', 'max' => '100']
            ])
            ->add('tauxMin', NumberType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['min' => '0', 'max' => '90']
            ])
            ->add('tauxMax', NumberType::class, [
                'required' => false,
                'label' => false,
                'attr' => ['min' => '0', 'max' => '90']
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => "button_validation"],
                'label' => 'filtrer les résultats'
            ])

            // Ajout des paramètres qui ne sont pas présents dans le form
            ->add('page', IntegerType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none']
            ])

            ->add('sort', TextType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none']
            ])

            ->add('direction', TextType::class, [
                'required' => false,
                'disabled' => true,
                'label' => false,
                'mapped' => false,
                'attr' => ['style' => 'display:none']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Filters::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
