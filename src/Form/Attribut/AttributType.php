<?php

namespace App\Form\Attribut;

use App\Entity\Attribut\Attribut;
use App\Form\Prix\PrixSansPromoType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Ex: XL'],
                ]
            )
            ->add(
                'prix',
                PrixSansPromoType::class,
                [
                    'required'=>false,
                    'attr' => ['data-help' => 'S\'ajoutera au prix de base'],
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Attribut::class,
            ]
        );
    }
}
