<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;

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
                                    'attr' => ['class' => 'input'],
                                    'help' => 'Votre mot de passe doit contenir au moins <span>8 caractères</span> 
                                    et au minimum : 1 majuscule, 1 minusule, 1 chiffre et 1 caractère spécial',
                                    'help_attr' => ['class' => 'help_message_password'],
                                    'help_html' => true,
                ],
                'second_options' => ['label' => 'Répétez le mot de passe',
                                    'attr' => ['class' => 'input']
                ],

                'attr' => ['autocomplete' => 'Nouveau mot de passe'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez rentrer un mot de passe',
                    ]),
                    new Regex([
                        'pattern' => '/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[#?!@$%^&*-]).{8,}$/',
                        'message' => 'Votre mot de passe n\'est pas valide'
                    ]),
                    new Length([
                        'min' => 8,
                        'minMessage' => 'votre mot de passe doit contenir au moins {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('envoyer', SubmitType::class, [
                'attr' => ['class' => "btn_register button_base button_base button_validation"],
                'label' => 'Modifier mon mot de passe'
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'review',
                'locale' => 'fr',
            ]);
    }

    // public function configureOptions(OptionsResolver $resolver): void
    // {
    //     $resolver->setDefaults([
    //         'data_class' => User::class,
    //     ]);
    // }
}
