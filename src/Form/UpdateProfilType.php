<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

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
        ->add('birthDate', BirthdayType::class, [
            'label' => 'Date de Naissance',
            'attr' => ['class' => 'input_date' ],
            'format' => 'ddMMyyyy',
            'input' => 'datetime_immutable',
            'disabled' => true,
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
        ->add('captcha', Recaptcha3Type::class, [
            'constraints' => new Recaptcha3(),
            'action_name' => 'review',
            'locale' => 'fr',
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
