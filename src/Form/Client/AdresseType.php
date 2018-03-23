<?php

namespace App\Form\Client;

use App\Entity\Client\Adresse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdresseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('numero')
            ->add(
                'codePostal',
                IntegerType::class,
                [
                    'required' => true,
                ]
            )
            ->add('localite');
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Adresse::class,
            ]
        );
    }
}