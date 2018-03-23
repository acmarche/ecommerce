<?php

namespace App\Form\Attribut;

use App\Entity\Attribut\ListingAttributs;
use App\Entity\Attribut\ProduitListingAttribut;
use App\Entity\Commerce\Commerce;
use App\Event\AddListingAttributsFieldSubscriber;
use App\Repository\Attribut\ListingAttributsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitListingAttributType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'required',
                CheckboxType::class,
                [
                    'required' => false,
                    'attr' => ['data-help' => 'Le client devra choisir au moins un attribut'],
                ]
            )
            ->add(
                'multiple',
                CheckboxType::class,
                [
                    'required' => false,
                    'attr' => ['data-help' => 'Le client pourra choisir plusieurs attributs'],
                ]
            )
            ->add(
                'expand',
                CheckboxType::class,
                [
                    'required' => false,
                    'label' => 'Etendu',
                    'attr' => ['data-help' => 'L\'affichange se fera en case à cocher ou en bouton radio'],
                ]
            );

        $builder->addEventSubscriber(new AddListingAttributsFieldSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class' => ProduitListingAttribut::class,
            ]
        );
    }
}
