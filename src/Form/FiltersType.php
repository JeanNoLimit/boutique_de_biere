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
            ->add('searchProduct', TextType::class, [
                'required' => false,
            ])
            ->add('providers', EntityType::class, [
               'class' => Provider::class,
               'label' => 'Brasseries',
               'choice_label' => 'name',
               'expanded' => true,
               'multiple' => true,
               'required' => false,
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
                'label' => 'Styles de bière'
            ])
            ->add('min', MoneyType::class, [
                'required' => false,
                'divisor' => 100,
                'attr' => ['min' => '0', 'max' => '100']
            ])
            ->add('max', MoneyType::class, [
                'required' => false,
                'divisor' => 100,
                'attr' => ['min' => '0', 'max' => '100']
            ])
            ->add('tauxMin', NumberType::class, [
                'required' => false,
                'attr' => ['min' => '0', 'max' => '90']
            ])
            ->add('tauxMax', NumberType::class, [
                'required' => false,
                'attr' => ['min' => '0', 'max' => '90']
            ])

            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => "button_validation"],
                'label' => 'filtrer les résultats'
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

    public function getBlockPrefix()
    {
        return '';
    }
}
