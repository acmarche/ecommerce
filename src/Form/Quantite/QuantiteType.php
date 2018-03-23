<?php

namespace App\Form\Quantite;

use App\Entity\Quantite\Quantite;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuantiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'stock',
                IntegerType::class,
                [
                    'label' => 'Stock disponible',
                    'attr' => ['data-help' => 'Mettre -1 pour non limités'],
                    'data' => -1,
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Quantite::class,
            ]
        );
    }
}
