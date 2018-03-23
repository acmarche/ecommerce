<?php

namespace App\Form\Commande;

use App\Entity\Commande\CommandeProduit;
use App\Event\AddCommandeProduitListingFieldSubscriber;
use App\Event\AddQuantiteFieldSubscriber;
use App\Service\TvaService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommandeProduitType extends AbstractType
{
    /**
     * @var TvaService
     */
    private $tvaService;

    public function __construct(TvaService $tvaService)
    {

        $this->tvaService = $tvaService;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new AddQuantiteFieldSubscriber());
        $builder->addEventSubscriber(new AddCommandeProduitListingFieldSubscriber($this->tvaService));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'data_class' => CommandeProduit::class,
            )
        );
    }

}
