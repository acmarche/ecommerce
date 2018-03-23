<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 13/09/17
 * Time: 17:00
 */

namespace App\Event;

use App\Service\MailerService;
use App\Entity\Security\User;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;


class CommandeSubscriber implements EventSubscriberInterface
{
    private $em;
    private $mailer;
    private $token;
    private $session;

    public function __construct(
        ObjectManager $em,
        MailerService $mailer,
        TokenStorageInterface $tokenStorage,
        SessionInterface $session
    ) {
        $this->em = $em;
        $this->mailer = $mailer;
        $this->token = $tokenStorage;
        $this->session = $session;
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
            CommandeEvent::COMMANDE_PAYE => 'commandePaye',
            CommandeEvent::COMMANDE_LIVRE => 'commandeLivre',
        ];

    }

    public function commandePaye(CommandeEvent $event)
    {
        $commande = $event->getCommande();
        $commerce = $commande->getCommerce();
        $userNameCommerce = $commerce->getUser();

        $from = $this->getUser($commande->getUser());

        $to = [];
        $to[] = "adl@marche.be";
        $to[] = "jf@marche.be";
        $to[] = $this->getUser($userNameCommerce);

        $this->mailer->sendCommandePaye($commande, $from, $to);
    }

    public function commandeLivre(CommandeEvent $event)
    {
        $commande = $event->getCommande();
        $commerce = $commande->getCommerce();
        $userNameCommerce = $commerce->getUser();

        $from = $this->getUser($commande->getUser());

        $to = [];
        $to[] = "adl@marche.be";
        $to[] = $this->getUser($userNameCommerce);

        $this->mailer->sendCommandeLivre($commande, $from, $to);
    }

    protected function getUser($username)
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['username' => $username]);
        if ($user instanceof User) {
            return $user->getEmail();
        } else {
            return "adl@marche.be";
        }
    }
}