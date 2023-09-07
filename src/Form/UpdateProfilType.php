<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UpdateProfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {


        $builder

        ->add('lastName', TextType::class, [
            'label' => 'Nom',
            'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '50']
        ])
        ->add('firstName', TextType::class, [
            'label' => 'Prénom',
            'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '50']
        ])
        ->add('adress', TextType::class, [
            'label' => 'Adresse',
            'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '150']
        ])
        ->add('zipCode', TextType::class, [
            'label' => 'Code postal',
            'attr' => ['class' => 'input', 'min' => '5', 'maxlength' => '5']
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '50']
        ])
        ->add('tel', TelType::class, [
            'label' => 'Num. de téléphone',
            'attr' => ['class' => 'input', 'min' => '10', 'maxlength' => '14']
        ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}