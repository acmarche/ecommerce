<?php

namespace App\Form\Search;

use App\Entity\Commerce\Commerce;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RechercheProduit extends AbstractType
{
    private $em;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $commerces = $this->em->getRepository(Commerce::class)->getForSearch();

        $builder
            ->add(
                'motclef',
                SearchType::class,
                [
                    'label' => 'Mot clef',
                    'required' => false,
                    'attr' => ['placeholder' => 'Mot clef'],
                ]
            )
            ->add(
                'commerce',
                ChoiceType::class,
                [
                    'choices' => $commerces,
                    'required' => false,
                    'placeholder' => 'Choisissez un commerce',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Rechercher',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

}
