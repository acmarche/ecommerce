<?php

namespace App\Form\Search;

use App\Entity\Commerce\Commerce;
use App\Entity\Livraison\LieuLivraison;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class SearchCommandeType extends AbstractType
{
    private $tokenStorage;
    private $em;

    public function __construct(EntityManagerInterface $em, TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $lieux = $this->em->getRepository(LieuLivraison::class)->getForSearch();

        $builder
            ->add(
                'idcommande',
                IntegerType::class,
                [
                    'required' => false,
                    'attr' => ['placeholder' => 'Numéro de commande'],
                ]
            )
            ->add(
                'user',
                TextType::class,
                [
                    'required' => false,
                    'label' => 'utilisateur',
                    'attr' => ['placeholder' => "Nom d'utilisateur"],
                ]
            )
            ->add(
                'lieu_livraison',
                ChoiceType::class,
                [
                    'choices' => $lieux,
                    'required' => false,
                    'placeholder' => 'Choisissez un lieu de livraison',
                ]
            )
            ->add('submit', SubmitType::class, ['label' => 'Rechercher'])
            ->add('raz', SubmitType::class);


        $user = $this->tokenStorage->getToken()->getUser();
        if (!$user) {
            throw new \LogicException(
                'The FriendMessageFormType cannot be used without an authenticated user!'
            );
        }

        if ($user->hasRole('ROLE_ECOMMERCE_LOGISTICIEN')) {
            $commerces = $this->em->getRepository(Commerce::class)->getForSearch();
        } else {
            $commerces = $this->em->getRepository(Commerce::class)->getForSearch($user);
        }
        $builder->add(
            'commerce',
            ChoiceType::class,
            [
                'choices' => $commerces,
                'required' => false,
                'placeholder' => 'Choisissez un commerce',
            ]
        );

        /*  $builder->addEventListener(
              FormEvents::PRE_SET_DATA,
              function (FormEvent $event) use ($commerces) {
                  $form = $event->getForm();
                  $form->add(
                      'commerce',
                      ChoiceType::class,
                      [
                          'choices' => $commerces,
                          'required' => false,
                          'placeholder' => 'Choisissez un commerce',
                      ]
                  );
              }
          );*/

    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array());
    }

}
