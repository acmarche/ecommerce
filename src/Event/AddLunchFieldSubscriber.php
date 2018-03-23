<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 16/03/18
 * Time: 11:48
 */

namespace App\Event;

use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Lunch\Ingredient;
use App\Repository\Lunch\IngredientRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddLunchFieldSubscriber implements EventSubscriberInterface
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
         * @var ProduitInterface
         */
        $produit = $event->getData();
        $form = $event->getForm();

        if ($produit->getIsFood()) {
            $commerce = $produit->getCommerce();
            $form->add(
                'ingredients',
                EntityType::class,
                [
                    'class' => Ingredient::class,
                    'query_builder' => function (IngredientRepository $er) use ($commerce) {
                        return $er->getCommerceForForm($commerce);
                    },
                    'multiple' => true,
                    'expanded' => true,
                    'required' => false,
                ]
            );
        }
    }
}