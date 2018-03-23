<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:00
 */

namespace App\Entity\TraitDef;

use App\Entity\InterfaceDef\ProduitInterface;
use Doctrine\ORM\Mapping as ORM;

trait ProduitTrait
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit\Produit", inversedBy="commande_produits")
     * 
     * @var
     */
    protected $produit;

    /**
     * @return ProduitInterface
     */
    public function getProduit()
    {
        return $this->produit;
    }

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit)
    {
        $this->produit = $produit;
    }

}
