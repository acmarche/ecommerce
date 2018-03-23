<?php

namespace App\Form\Produit;

use App\Entity\Categorie\Categorie;
use App\Entity\Produit\Produit;
use App\Event\AddLunchFieldSubscriber;
use App\Form\Prix\PrixSansPromoType;
use App\Form\Prix\PrixType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('description')
            ->add(
                'indisponible',
                CheckboxType::class,
                [
                    'attr' => ['data-help' => 'Le produit ne sera plus visible sur le site'],
                    'required' => false,
                ]
            )
            ->add(
                'prix',
                PrixType::class
            )
            ->add(
                'quantiteStock',
                IntegerType::class,
                [
                    'label' => 'Stock disponible',
                    'attr' => ['data-help' => 'Mettre -1 pour non limités'],
                ]
            )
            ->add(
                'tvaApplicable',
                PercentType::class,
                [
                    'scale' => 2,
                    'type' => 'integer',
                    'required' => false,
                    'attr' => ['data-help' => 'Si différente de celle définie sur le commerce'],
                ]
            )
            ->add(
                'categorie',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Categorie::class,
                    'placeholder' => 'Sélectionnez une catégorie',
                ]
            )
            ->add('dimension', ProduitDimensionType::class);

        $builder->addEventSubscriber(new AddLunchFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Produit::class
            )
        );
    }

}
