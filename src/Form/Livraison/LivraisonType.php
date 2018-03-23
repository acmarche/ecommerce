<?php

namespace App\Form\Livraison;

use App\Entity\Client\Adresse;
use App\Entity\Livraison\LieuLivraison;
use App\Form\Client\AdresseType;
use App\Repository\Client\AdresseRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class LivraisonType extends AbstractType
{
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;
    /**
     * @var AdresseRepository
     */
    private $adresseRepository;

    public function __construct(TokenStorageInterface $tokenStorage, AdresseRepository $adresseRepository)
    {
        $this->tokenStorage = $tokenStorage;
        $this->adresseRepository = $adresseRepository;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $username = $this->tokenStorage->getToken()->getUsername();
        $builder
            ->add(
                'dateLivraison',
                DateType::class,
                [
                    'label' => 'Date de livraison souhaitée',
                    'widget' => 'choice',
                ]
            )
            ->add(
                'lieuLivraison',
                EntityType::class,
                [
                    'class' => LieuLivraison::class,
                    'required' => true,
                    'placeholder' => 'Choisissez un lieu de livraison',
                ]
            )
            ->add(
                'adresse',
                EntityType::class,
                [
                    'label' => 'Adresse de facturation',
                    'class' => Adresse::class,
                    'query_builder' => function (AdresseRepository $er) use ($username) {
                        return $er->getByUserForForm($username);
                    },
                    'choice_label' => 'nomruelocalite',
                    'placeholder' => 'Sélectionnez une adresse',
                ]
            )
            ->add(
                'submit',
                SubmitType::class,
                [
                    'label' => 'Valider',
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(//   'data_class' => commande::class,
            )
        );
    }

}
