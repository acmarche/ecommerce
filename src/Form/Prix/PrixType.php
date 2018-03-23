<?php

namespace App\Form\Prix;

use App\Entity\Prix\Prix;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrixType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'htva',
                MoneyType::class,
                [
                    'label' => 'Prix Htva',
                    'required' => true,
                ]
            )
            ->add(
                'promo_htva',
                MoneyType::class,
                [
                    'required'=>false,
                    'label' => 'Prix promo Htva',
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => Prix::class,
            ]
        );
    }
}
