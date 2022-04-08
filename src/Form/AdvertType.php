<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Car;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Security\Core\Security;

class AdvertType extends AbstractType
{
    private $security;
    
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('titre', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un titre"
                ]),
                "label" => "Titre de l'annonce",
                "attr"=>[
                    "placeholder"=>"Titre",
                    "class"=>"form-control col-6"
                ]

            ])
            ->add('description', TextareaType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une description"
                ]),
                "label" => "Description de l'annonce",
                "attr"=>[
                    "class"=>"form-control"
                 ]

            ])
            ->add('car', EntityType::class,[
                "class"=> Car::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.customer = :customer")
                        ->setParameter(":customer", $this->security->getUser()->getCustomer())
                        ->orderBy('c.name', 'ASC');
                },
                "choice_label"=> 'name',
                "label" => "Choisissez une voiture",
                "attr"=>[
                    "class"=>"form-control col-offset-4 col-4"
                ]
            ])
            ->add('prix', MoneyType::class, [
                "required" => false,

                "constraints" => new NotBlank([
                    "message" => "Mettre un prix"
                ]),
                "label" => "Prix de la location par jour",
                "attr"=>[
                    "class"=>"form-control col-offset-4 col-2"
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
