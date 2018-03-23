<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 22:14
 */

namespace App\Checker;

use App\Entity\InterfaceDef\ProduitInterface;

class ProduitChecker
{
    /**
     * Le produit est-il disponible sur le marche
     * @param ProduitInterface $produit
     * @return bool
     */
    function checkDisponible(ProduitInterface $produit): bool
    {
        return !$produit->getIndisponible();
    }

    /**
     * Disponible ? En stock ?
     * @param ProduitInterface $produit
     * @return bool
     */
    function canBuy(ProduitInterface $produit): bool
    {
        if ($this->checkDisponible($produit)) {
            if ($this->checkStock($produit)) {
                return true;
            }
        }

        return false;
    }

    function canDisplayOnsite(ProduitInterface $produit): bool
    {
        if ($this->checkDisponible($produit)) {
            return true;
        }

        return false;
    }

    /**
     * @param ProduitInterface $produit
     * @return bool
     */
    public function checkStock(ProduitInterface $produit): bool
    {
        if ($produit->getQuantiteStock() == 0) {
            return false;
        }

        return true;
    }
}