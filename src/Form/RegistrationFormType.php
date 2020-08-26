<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email',EmailType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Email manquant.']),
                    new Length([
                        'max' => 100,
                        'maxMessage' => 'L\'adresse email ne peu contenur okys de {{ limit}} catactères.'

                    ]),
                    new Email(['message' => 'cette adresse n\'est pas uen adresse email valide.'])
                ]
                ])
                    

            ->add('pseudo',TextType::class,[
                'constraints' => [
                    new NotBlank(['message' => 'Pseudo manquant']),
                    new Length([
                        'min' =>3,
                        'minMessage' =>'Le pseudo doit contenir au moins {{limit}} caractère',
                        'max' =>30,
                        'maxMessage' =>'le pseudo ne peut contenir plus de {{ limit }} caractère,'
                        
                    ]),
                    new Regex([
                        'pattern' =>'/^[a-zA-Z0-9_-]+$/',
                        'message' => 'ne peu contenir que desz ciffres ,lettres , tirets et underscores'
                    ])
                ]
            ])
            ->add('plainPassword', RepeatedType::class,[
                'type' => Passwordtype::class,
                'invalid_message' => 'Les mots de passe de corréspondent pas.',
                
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                // le champ n'est pas lié a l'objet User du formulaire.
                // le mot de passe sera hashé depuis le controlleur
                'mapped' => false,
                'constraints' => [
                    new NotBlank(['message' => 'Mots de passe manquant',]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
