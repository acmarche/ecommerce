<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/03/18
 * Time: 11:48
 */

namespace App\Event;

use App\Entity\Attribut\Attribut;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Service\TvaService;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddCommandeProduitListingFieldSubscriber implements EventSubscriberInterface
{
    /**
     * @var TvaService
     * //todo faire autrement !
     */
    private $tvaService;

    public function __construct(TvaService $tvaService)
    {

        $this->tvaService = $tvaService;
    }

    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $form = $event->getForm();

        /**
         * @var CommandeProduitInterface $commandeProduit
         */
        $commandeProduit = $event->getData();

        /**
         * @var ProduitInterface $produit
         */
        $produit = $commandeProduit->getProduit();

        foreach ($produit->getProduitListingsAttributs() as $listingAttribut) {
            $choices = [];
            foreach ($listingAttribut->getListingAttributs()->getAttributs() as $attribut) {
                $nom = $attribut->getNom();

                if ($attribut->getPrixApplique() > 0) {
                    $prix = $this->tvaService->getPrixAttributTvac($attribut, $produit);
                    $nom .= ' (+'.$prix.' €)';
                }

                $choices[$nom] = $attribut->getId();
            }
            $form->add(
                'attributs-'.$listingAttribut->getId(),
                ChoiceType::class,
                [
                    'label' => $listingAttribut->getListingAttributs()->getNom(),
                    'choices' => $choices,
                    'required' => $listingAttribut->isRequired(),
                    'multiple' => $listingAttribut->isMultiple(),
                    'expanded' => $listingAttribut->isExpand(),
                    'mapped' => false,
                ]
            );
        }
    }
}