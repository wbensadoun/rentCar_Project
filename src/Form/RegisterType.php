<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un nom"
                ]),
                "label" => "Votre nom"

            ])
            ->add('firstName', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un prénom"
                ]),
                "label" => "Votre prénom"

            ])
            ->add('address', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une adresse"
                ]),
                "label" => "Votre adresse"

            ])
            ->add('city', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre une ville"
                ]),
                "label" => "Votre ville"

            ])
            ->add('postalCode', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un code postale"
                ]),
                "label" => "Votre code postale"

            ])


            ->add('phone', TextType::class, [
                "required" => false,
                "constraints" => new NotBlank([
                    "message" => "Mettre un numéro de téléphone"
                ]),
                "label" => "Votre numéro de téléphone"

            ])
            ->add("user", UserType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
