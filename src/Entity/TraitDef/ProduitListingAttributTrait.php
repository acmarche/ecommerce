<?php
namespace App\Entity\TraitDef;

use App\Entity\Attribut\ProduitListingAttribut;
use Doctrine\ORM\Mapping as ORM;

trait ProduitListingAttributTrait
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Attribut\ProduitListingAttribut", mappedBy="produit", cascade={"remove"})
     * @var ProduitListingAttribut[]
     */
    private $produitListingsAttributs;

    /**
     * @return ProduitListingAttribut[]
     */
    public function getProduitListingsAttributs()
    {
        return $this->produitListingsAttributs;
    }

    /**
     * @param ProduitListingAttribut[] $produitListingsAttributs
     */
    public function setProduitListingsAttributs(array $produitListingsAttributs): void
    {
        $this->produitListingsAttributs = $produitListingsAttributs;
    }


}