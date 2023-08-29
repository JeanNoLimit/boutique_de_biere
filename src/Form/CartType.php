<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\LessThan;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;


class CartType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity', IntegerType::class, [
                'attr' => ['class' => "input_number", 'min' => "1", 'max' =>"99", 'step' => "1", 'value' =>"1"],
                'label' => 'Quantité',
                'invalid_message' => "Veuillez rentrer une quantité valide (commprise entre 1 et 99)",
                'required' => true,
                'empty_data' => 1,
                'constraints' => [
                    new Positive(),
                    new GreaterThanOrEqual(1),
                    new LessThan(99)
                ]
            ])
            ->add('Envoyer', SubmitType::class, [
                'attr' => ['class' => "button_validation"],
                'label' => 'Ajouter au panier'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
