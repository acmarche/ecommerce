<?php

namespace App\Form\Attribut;

use App\Entity\Attribut\ListingAttributs;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ListingAttributsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'attr' => ['placeholder' => 'Ex: Tailles vêtements'],
                ]
            )
            ->add(
                'attributs',
                CollectionType::class,
                [
                    'entry_type' => AttributType::class,
                    'entry_options' => array('label' => false),
                    'allow_add' => true,
                    'allow_delete' => true,
                    'by_reference' => false,// pour ne pas que sf utilise le setAttributs()
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ListingAttributs::class,
            ]
        );
    }
}
