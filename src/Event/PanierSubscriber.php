<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 12:10
 */

namespace App\Event;

use App\Checker\PanierChecker;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Manager\PanierManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class PanierSubscriber implements EventSubscriberInterface
{
    private $em;
    private $panierManager;
    private $panierChecker;
    private $flashBag;
    private $urlGenerator;

    public function __construct(
        ObjectManager $em,
        FlashBagInterface $flashBag,
        PanierManager $panierManager,
        PanierChecker $panierChecker,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->em = $em;
        $this->panierManager = $panierManager;
        $this->panierChecker = $panierChecker;
        $this->flashBag = $flashBag;
        $this->urlGenerator = $urlGenerator;
    }

    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array keys are event names and the value can be:
     *
     *  * The method name to call (priority defaults to 0)
     *  * An array composed of the method name to call and the priority
     *  * An array of arrays composed of the method names to call and respective
     *    priorities, or 0 if unset
     *
     * For instance:
     *
     *  * array('eventName' => 'methodName')
     *  * array('eventName' => array('methodName', $priority))
     *  * array('eventName' => array(array('methodName1', $priority), array('methodName2')))
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            //   KernelEvents::RESPONSE => array(
            //        array('onKernelResponsePost', -10),
            //   ),
            PanierEvent::PANIER_INDEX => 'panierIndex',
        ];
    }

    /**
     * @param PanierEvent $event
     * @return CommandeInterface[]
     */
    public function panierIndex(PanierEvent $event)
    {
        $commandes = $event->getCommandes();
        try {
            $commandes = $this->panierChecker->checkAllPanier($commandes);
        } catch (\Exception $e) {
            $this->flashBag->add('danger', $e->getMessage());
        }

        return $commandes;
    }

    public function onKernelResponsePost(FilterResponseEvent $event)
    {
        $event->setResponse(new RedirectResponse($this->urlGenerator->generate('acecommerce_home')));
    }

}