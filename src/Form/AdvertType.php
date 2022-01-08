<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\Car;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
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
                "label" => "Titre de l'annonce"
            ])
            ->add('description', CKEditorType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une description"
                ]),
                "label" => "Description de l'annonce"
            ])
            ->add('car', EntityType::class,[
                "class"=> Car::class,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('c')
                        ->where("c.customer = :customer")
                        ->setParameter(":customer", $this->security->getUser()->getCustomer())
                        ->orderBy('c.name', 'ASC');
                },
                "choice_label"=> 'name'

            ])
            ->add('prix', MoneyType::class, [
                "required" => false,
                "attr" => [
                    "class" => "form-control"
                ],
                "constraints" => new NotBlank([
                    "message" => "Mettre un prix"
                ]),
                "label" => "Prix de la location par jour"
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
