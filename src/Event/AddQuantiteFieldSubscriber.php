<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/03/18
 * Time: 11:48
 */

namespace App\Event;

use App\Entity\InterfaceDef\CommandeProduitInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddQuantiteFieldSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents()
    {
        // Tells the dispatcher that you want to listen on the form.pre_set_data
        // event and that the preSetData method should be called.
        return array(FormEvents::PRE_SET_DATA => 'preSetData');
    }

    public function preSetData(FormEvent $event)
    {
        /**
         * @var CommandeProduitInterface
         */
        $commandeProduit = $event->getData();
        $form = $event->getForm();
        $data = 1;
        $label = 'Ajouter';

        if ($commandeProduit && null != $commandeProduit->getId()) {
            $data = $commandeProduit->getQuantite();
            $label = 'Mettre à jour';
        }

        $form->add(
            'quantite',
            IntegerType::class,
            [
                'data' => $data,
                'attr' => ['autocomplete' => 'off'],
            ]
        )
            ->add('submit', SubmitType::class, ['label' => $label]);
    }
}