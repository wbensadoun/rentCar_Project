<?php

namespace App\Form;

use App\Entity\Advert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;

class AdvertType extends AbstractType
{
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
            ->add('prix', CurrencyType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un prix"
                ]),
                "label" => "Prix de la location par jour"
            ])
            ->add('Car');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
