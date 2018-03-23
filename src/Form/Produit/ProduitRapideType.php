<?php

namespace App\Form\Produit;

use App\Entity\Categorie\Categorie;
use App\Entity\Produit\Produit;
use App\Event\AddLunchFieldSubscriber;
use App\Form\Prix\PrixSansPromoType;
use App\Form\Prix\PrixType;
use App\Form\Quantite\QuantiteType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitRapideType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'nom',
                TextType::class,
                [
                    'block_name' => 'custom_name',
                ]
            )
            ->add('description')
            ->add(
                'prix',
                PrixSansPromoType::class
            )
            ->add(
                'quantite',
                QuantiteType::class,
                [
                    'label' => 'Stock disponible',
                    'attr' => ['data-help' => 'Mettre -1 pour non limités'],
                ]
            )
            ->add(
                'categorie',
                EntityType::class,
                [
                    'required' => true,
                    'class' => Categorie::class,
                    'placeholder' => 'Sélectionnez une catégorie',
                    'group_by' => function ($value, $key, $index) {
                    $parent = $value->getParent();
                        if ($parent && $parent->getNom() == 'Ecommerce' ) {
                            return 'Ecommerce';
                        } else {
                            return 'Lunch';
                        }
                    },
                ]
            );

        $builder->addEventSubscriber(new AddLunchFieldSubscriber());
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => Produit::class,
            )
        );
    }

}
