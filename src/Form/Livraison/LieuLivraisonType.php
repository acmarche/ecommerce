<?php

namespace App\Form\Livraison;

use App\Entity\Livraison\LieuLivraison;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LieuLivraisonType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('rue')
            ->add('numero')
            ->add('codePostal', IntegerType::class, ['required' => true])
            ->add('localite')
            ->add('nom')
            ->add(
                'description',
                TextareaType::class,
                [
                    'required' => false,
                ]
            );
        /*   ->add(
               'commerce',
               EntityType::class,
               [
                   'required' => false,
                   'class' => dommerce::class,
               ]) */

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => LieuLivraison::class,
            )
        );
    }

}
