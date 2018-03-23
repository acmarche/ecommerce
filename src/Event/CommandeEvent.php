<?php

namespace App\Event;

use App\Entity\InterfaceDef\CommandeInterface;
use App\Service\EcommerceConstante;
use Symfony\Component\EventDispatcher\Event;

/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 13/09/17
 * Time: 16:053
 */
class CommandeEvent extends Event
{
    const COMMANDE_NEW = EcommerceConstante::COMMANDE_NEW;
    const COMMANDE_PAYE = EcommerceConstante::COMMANDE_PAYE;
    const COMMANDE_VALIDE = EcommerceConstante::COMMANDE_VALIDE;
    const COMMANDE_LIVRE = EcommerceConstante::COMMANDE_LIVRE;

    protected $commande;

    public function __construct(CommandeInterface $commande)
    {
        $this->commande = $commande;
    }

    public function getCommande()
    {
        return $this->commande;
    }
}