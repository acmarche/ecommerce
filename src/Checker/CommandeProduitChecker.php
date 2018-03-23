<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 22:04
 */

namespace App\Checker;


use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\Security\User;

class CommandeProduitChecker
{
    /**
     * Utilise lors d'un update
     * @param CommandeProduitInterface $commandeProduit
     * @param User $user
     * @throws \Exception
     */
    public function checkCommandeProduit(CommandeProduitInterface $commandeProduit)
    {
        $commande = $commandeProduit->getCommande();

        if (!$commande) {
            throw new \Exception('commande introuvable');
        }

        $produit = $commandeProduit->getProduit();

        if (!$produit) {
            throw new \Exception('produit introuvable');
        }

        $commerce = $commande->getCommerce();
        if (!$commerce) {
            throw new \Exception('commerce introuvable');
        }

        if (!$this->commandeProduitExistInPanier($commandeProduit)) {
            throw new \Exception("commande produit inexsistante");
        }
    }

}