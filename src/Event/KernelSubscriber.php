<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 31/03/18
 * Time: 14:47
 */

namespace App\Event;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * Class KernelSubscriber
 * NOT USE peut être plus tard
 * @package App\Event
 */
class KernelSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(EntityManagerInterface $entityManager, KernelInterface $kernel)
    {
        $this->entityManager = $entityManager;
        $this->kernel = $kernel;
    }

    public static function getSubscribedEvents()
    {
        return[];
        // return the subscribed events, their methods and priorities
        return array(
            KernelEvents::REQUEST => array(
                array('log', 0),
            ),
        );
    }

    public function log(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ($this->kernel->getEnvironment() == "dev") {

        }
        $request->getClientIp();
        $request->getUser();
        $request->getRequestUri();
    }

}