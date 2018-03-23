<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/03/18
 * Time: 11:48
 */

namespace App\Event;

use App\Entity\Attribut\ListingAttributs;
use App\Repository\Attribut\ListingAttributsRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddListingAttributsFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        $listing = $event->getData();
        $form = $event->getForm();
        $commerce = $listing->getProduit()->getCommerce();

        if (!$listing || null === $listing->getId()) {
            $form->add(
                'listingAttributs',
                EntityType::class,
                [
                    'class' => ListingAttributs::class,
                    'placeholder' => 'Sélectionnez',
                    'query_builder' => function (ListingAttributsRepository $er) use ($commerce) {
                        return $er->getListForForm($commerce);
                    },
                ]
            );
        }
    }
}