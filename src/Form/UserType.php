<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                "required"=> true,
                "label"=>"Mettez une adresse mail",
                "attr"=>[
                    "placeholder"=>"Email"
                ]
            ])

            ->add("confirmPassword", RepeatedType::class,  [
            "required"=>true,
            "mapped"=>false,
            "first_options"=>[
                "label"=>"Mot de passe"
            ],
            "second_options"=>[
                "label"=>"Confirmez le mot de passe"
            ],
            "invalid_message"=>"Les mots de passe ne correspondent pas",
            "type"=>PasswordType::class
        ])
            ->add('userName', TextType::class, [
                "required"=>true,
                "label"=>'entrez un pseudo'
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
