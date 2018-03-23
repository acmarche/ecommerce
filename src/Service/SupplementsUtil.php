<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 12/07/17
 * Time: 17:00
 */

namespace App\Service;

use App\Entity\Commande\Commande;
use App\Entity\Commande\CommandeProduit;

class SupplementsUtil
{
    /**
     * @param CommandeProduit $commandeProduit
     * @return int
     */
    public function getCoutSupplements(CommandeProduit $commandeProduit)
    {
        $total = 0;
        foreach ($commandeProduit->getCommandeProduitSupplements() as $cmSupplement) {
            $total += $cmSupplement->getSupplement()->getPrix();
        }
        return $total;
    }

    /**
     * @param Commande $commande
     * @return int
     */
    public function getCoutSupplementsByCommande(Commande $commande)
    {
        $commandeProduits = $commande->getCommandeProduits();
        $total = 0;
        foreach ($commandeProduits as $commandeProduit) {
            $total += $this->getCoutSupplements($commandeProduit);
        }
        return $total;
    }
}
