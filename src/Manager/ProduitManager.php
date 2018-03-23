<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 12:11
 */

namespace App\Manager;

use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Produit\Produit;

class ProduitManager extends AbstractManager
{
    public function create()
    {
        return new Produit();
    }

    public function insertProduit(ProduitInterface $produit)
    {
        $this->entityManager->persist($produit);
        $this->flush();
    }
    
    public function updateProduit(ProduitInterface $produit)
    {        
        $this->flush();
    }

}