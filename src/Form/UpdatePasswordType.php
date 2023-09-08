<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Validator\Constraints as Assert;

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('oldPlainPassword', PasswordType::class, [
                'invalid_message' => 'Le mot de passe ne correspond pas',
                'label' => 'Mot de passe actuel',
                'attr' => ['class' => 'input'],
                
                'constraints' => [new Assert\NotBlank()]
            ])
            // mapped => false signifie que le champ ne sera pas stocké en bdd (le mot de passe sera hashé)
            ->add('newPlainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'first_options' => ['label' => 'Nouveau mot de passe',
                                    'attr' => ['class' => 'input']
                ],
                'second_options' => ['label' => 'Répétez le mot de passe',
                                    'attr' => ['class' => 'input']
                ],
                
                'attr' => ['autocomplete' => 'Nouveau mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => "btn_register button_validation"],
                'label' => 'Modifier mon mot de passe'
            ]);
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => User::class,
    //     ]);
    // }
}
