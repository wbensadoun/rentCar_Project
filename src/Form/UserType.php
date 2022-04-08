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
use Symfony\Component\Validator\Constraints\NotBlank;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $action = $options["action"];

        $builder
            ->add('email', EmailType::class, [
                "required"=> true,
                "constraints" => new NotBlank([
                    "message" => "Mettre un numéro de téléphone"
                ]),
                "label"=>"Mettre une adresse mail",
                "attr"=>[
                    "placeholder"=>"Email",
                    "class"=>"form-control"
                ]
            ])

            ->add('userName', TextType::class, [
                "required"=>true,
                "constraints" => new NotBlank([
                    "message" => "Mettre un numéro de téléphone"
                ]),
                "label"=>"Choisissez un nom d'utilisateur",
                "attr"=>[
                    "placeholder"=>"Nom d'utilisateur",
                    "class"=>"form-control"
                ]
            ])
        ;
        if($action=="profile"){
           $builder ->add("confirmPassword", RepeatedType::class,  [
                "required"=>false,
                "mapped"=>false,
                "first_options"=>[
                    "attr"=>[
                        "placeholder"=>"Mot de passe",
                        "class"=>"form-control"
                    ]
                ],
                "second_options"=>[
                    "attr"=>[
                        "placeholder"=>"Confirmez le mot de passe",
                        "class"=>"form-control"
                    ]
                ],
                "invalid_message"=>"Les mots de passe ne correspondent pas",
                "type"=>PasswordType::class
            ]);

        }elseif($action == "register"){

            $builder ->add("confirmPassword", RepeatedType::class,  [
                    "required"=>true,
                    "mapped"=>false,
                    "first_options"=>[
                        "label"=>"Mot de passe",
                        "attr"=>[
                            "placeholder"=>"Mot de passe",
                            "class"=>"form-control"
                        ]
                    ],
                    "second_options"=>[
                        "label"=>"Confirmez le mot de passe",
                        "attr"=>[
                            "placeholder"=>"Confirmez le mot de passe",
                            "class"=>"form-control"
                        ]
                    ],
                    "invalid_message"=>"Les mots de passe ne correspondent pas",
                    "type"=>PasswordType::class
            ]);

        }
    }
//Le formulaire UserType est appelé dans plusieurs formulaires il ne faut pas effacé les champs de UserType
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'action' => null
        ]);
    }
}
