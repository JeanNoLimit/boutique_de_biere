<?php

namespace App\Form;

use App\Entity\User;
use DateTimeImmutable;
use Symfony\Component\Form\AbstractType;
use Karser\Recaptcha3Bundle\Form\Recaptcha3Type;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Karser\Recaptcha3Bundle\Validator\Constraints\Recaptcha3;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '180']
            ])
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '15' ]
            ])
            // mapped => false signifie que le champ ne sera pas stocké en bdd (le mot de passe sera hashé)
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'first_options' => ['label' => 'Mot de passe',
                                    'attr' => ['class' => 'input'],
                                    'help' => 'Votre mot de passe doit contenir au moins <span>8 caractères</span> 
                                    et au minimum : 1 majuscule, 1 minusule, 1 chiffre et 1 caractère spécial',
                                    'help_attr' => ['class' => 'help_message_password'],
                                    'help_html' => true,
                ],
                'second_options' => ['label' => 'Répétez le mot de passe',
                                    'attr' => ['class' => 'input']
                ],
                'mapped' => false,
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
            ->add('lastName', TextType::class, [
                'label' => 'Nom',
                'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '50']
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'attr' => ['class' => 'input', 'min' => '2', 'maxlength' => '50']
            ])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de Naissance*',
                'attr' => ['class' => 'input_date'],
                'format' => 'ddMMyyyy',
                'input' => 'datetime_immutable',
                'invalid_message' => 'Veuillez rentrer une date valide',
                'data' => new DateTimeImmutable(),
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
            ->add('agreeTerms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions générales d\'utilisation du site
                            et sa politique de confidentialité',
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter nos conditions générales afin d\'être inscrit(e) sur le site.'
                    ]),
                ],
            ])
            ->add('captcha', Recaptcha3Type::class, [
                'constraints' => new Recaptcha3(),
                'action_name' => 'registration',
                'locale' => 'fr',
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'constraints' => [
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'email',
                    'message' => 'Cette adresse est déja enregistrée'
                ]),
                new UniqueEntity([
                    'entityClass' => User::class,
                    'fields' => 'pseudo',
                    'message' => 'Ce pseudo existe déja'
                ]),
            ],
        ]);
    }
}
