<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;


class CustomerType extends AbstractType
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
            ->add('firstName')
            ->add('address')
            ->add('city')
            ->add('postalCode')
            ->add('email')
            ->add('phone')
            ->add('licencePicture', FileType::class, [
                'label' => 'Permis de conduire ',
                'data_class' => null, //Sur un champ de type file on doit obligatoirement  avoir data_class -> null

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => false,

            ])
            ;
          }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }


}


