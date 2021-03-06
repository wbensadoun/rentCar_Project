<?php

namespace App\Form;

use App\Entity\Car;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class CarType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class,[
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un nom"
                ]),
                "label" => "Nom de la voiture",
                "attr"=>[
                    "class"=>"form-control"
                ]

            ])
            ->add('description', TextareaType::class,[
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une description de la voiture(ex: kilométrage, énergie..."
                ]),
                "label" => "Description",
                "attr"=>[
                    "class"=>"form-control"
                ]

            ])
            ->add('numberPlate', TextType::class,[
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une plaque d'immatriculation"
                ]),
                "label" => "plaque d'immatriculation",
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
            ->add('startDate', DateType::class,[
                "widget"=> "single_text",
                "html5"=> true,
                "label"=> "Date d'ajout",
                "attr"=>[
                    "class"=>"form-control"
                ]
            ])
            ->add('model', TextType::class,[
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Choisissez un modèle"
                ]),
                "label" => "modèle",
                "attr"=>[
                    "class"=>"form-control"
                ]

            ])

            ->add('picture1', FileType::class, [
                'label' => 'Photo Principale de la voiture',
                'data_class' => null, //Sur un champ de type file on doit obligatoirement  avoir data_class -> null

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                "attr"=>[
                    "class"=>"form-control-file"
                ]
            ])
            ->add('picture2', FileType::class, [
                'label' => 'Photo supplémentaire',
                'data_class' => null, //Sur un champ de type file on doit obligatoirement  avoir data_class -> null

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                "attr"=>[
                    "class"=>"form-control-file"
                ]
            ])
            ->add('picture3', FileType::class, [
                'label' => 'Photo supplémentaire',
                'data_class' => null, //Sur un champ de type file on doit obligatoirement  avoir data_class -> null

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,
                "attr"=>[
                    "class"=>"form-control-file"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Car::class,
        ]);
    }
}
