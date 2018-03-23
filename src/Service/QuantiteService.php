<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 19:37
 */

namespace App\Service;

use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Security\User;
use App\Repository\Commande\CommandeProduitRepository;

class QuantiteService
{
    private $commandeProduitRepository;

    public function __construct(CommandeProduitRepository $commandeProduitRepository)
    {
        $this->commandeProduitRepository = $commandeProduitRepository;
    }

    /**
     * Pour afficher nbre d'item present de le panier
     * @param ProduitInterface $produit
     * @return int
     */
    public function countProduitInPanier(ProduitInterface $produit, User $user = null)
    {
        if (!$user) {
            return 0;
        }

        $quantiteDansPanier = 0;
        foreach ($this->commandeProduitRepository->getCommandesProduitByProduitInPanier($produit, $user) as $commandeProduit) {
            $quantiteDansPanier += $commandeProduit->getQuantite();
        }

        return $quantiteDansPanier;
    }

}