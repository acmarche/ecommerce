<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParamsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'default_tva',
                PercentType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'email_master',
                EmailType::class,
                [
                    'required' => false,
                ]
            )
            ->add(
                'stripe_secret_key',
                TextType::class,
                [
                    'required' => false,
                ]
            )->add(
                'stripe_public_key',
                TextType::class,
                [
                    'required' => false,
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(//  'data_class' => categorie::class
            )
        );
    }

}
