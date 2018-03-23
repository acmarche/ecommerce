<?php

namespace App\Form\Commande;

use App\Entity\InterfaceDef\CommandeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TraiterCommandeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'livre',
                CheckboxType::class,
                [
                    'label' => 'Livrée',
                ]
            )
            ->add(
                'livraisonRemarque',
                TextareaType::class,
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
            array(
                'data_class' => CommandeInterface::class,
            )
        );
    }

}
