<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 19/03/18
 * Time: 14:14
 */

namespace App\Checker;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Entity\InterfaceDef\ProduitInterface;

/**
 * //todo pas encore implemente
 * Class AttributChecker
 * @package App\Checker
 */
class AttributChecker
{
    /**
     * @param ProduitInterface $produit
     * @param $attributsSelectionne
     */
    public function isRequired(ProduitInterface $produit, $attributsSelectionne)
    {
        foreach ($produit->getProduitListingsAttributs() as $listingAttribut) {
            if ($listingAttribut->isRequired()) {
                $this->che($listingAttribut->getListingAttributs(), $attributsSelectionne);
            }
        }
    }

    /**
     * @param ListingAttributsInterface[] $listings
     * @param AttributInterface[] $attributsSelectionne
     */
    private function che($listings, $attributsSelectionne)
    {
        foreach ($listings as $listing) {
            foreach ($attributsSelectionne as $attribut) {
                $listing->getAttributs()->contains($attribut);
            }
        }
    }
}