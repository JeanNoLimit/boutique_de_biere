<?php

namespace App\Form;

use App\Entity\Review;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class ReviewType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('rating', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5
                ],
                'label' => 'Note / 5',
                'attr' => ['class' => ""],
                'invalid_message' => "La note doit être comprise entre 1 et 5",
                'required' => true,
            ])
            ->add('description', TextareaType::class, [
                'attr' => ['class' => "",'min' => "10", 'max' => "1000"],
                'label' => 'Détaillez votre avis',
                'required' => false,
            ])
            ->add('envoyer', SubmitType::class, [
                'row_attr' => ['class' =>'submit_review'],
                'attr' => ['class' => "button_base button_validation"],
                'label' => 'Ajouter un avis'
            ])

            // ->add('user')
            // ->add('product')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Review::class,
            'sanitize_html' => true,
        ]);
    }
}
