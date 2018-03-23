<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 14:30
 */

namespace App\Entity\InterfaceDef;


interface ProduitListingInterface
{
    /**
     * @return ProduitInterface
     */
    public function getProduit(): ProduitInterface;

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit): void;

    /**
     * @return ListingAttributsInterface
     */
    public function getListingAttributs(): ?ListingAttributsInterface;

    /**
     * @param ListingAttributsInterface $listingAttributs
     */
    public function setListingAttributs(ListingAttributsInterface $listingAttributs): void;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void;

    /**
     * @return bool
     */
    public function isMultiple(): bool;

    /**
     * @param bool $multiple
     */
    public function setMultiple(bool $multiple): void;

    /**
     * @return bool
     */
    public function isExpand(): bool;

    /**
     * @param bool $expand
     */
    public function setExpand(bool $expand): void;

}