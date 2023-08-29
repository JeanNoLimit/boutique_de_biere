<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Unique;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                // 'constraints' => [
                //     new NotNull([
                //         'message' => 'veuillez renseigner une adresse mail'
                //     ]),
                //     new Unique([
                //         'message' => 'Adresse mail non valide'
                //     ]),
                //     new Length(['max'=> '180'])
                // ]
            ])
            ->add('pseudo', TextType::class, [
                // 'constraints' => [
                //     new NotBlank([
                //         'message' => 'veuillez renseigner un pseudo'
                //     ]),
                //     new Unique([
                //         'message' => 'Pseudo déja utilisé'
                //     ]),
                //     new Length([
                //         'min'=>'2',
                //         'max'=> '50',
                //         'minMessage' => 'Veuillez selectionner un pseudo de plus de {{limit}} caractères',
                //         'maxMessage' => 'Veuillez selectionner un pseudo de moins de {{limit}} caractères'
                //     ])
                // ]
            ])
            // mapped => false signifie que le champ ne sera pas stocké en bdd (le mot de passe sera hashé)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' =>true,
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'répétez le mot de passe'],
                'mapped' => false,
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
            ->add('lastName', TextType::class)
            ->add('firstName', TextType::class)
            ->add('adress', TextType::class)
            ->add('zipCode', TextType::class)
            ->add('city', TextType::class)
            ->add('tel', TelType::class)
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
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
