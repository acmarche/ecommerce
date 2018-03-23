<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 14/03/18
 * Time: 14:10
 */

namespace App\Entity\InterfaceDef;


interface AttributInterface
{

    public function __toString();

    /**
     * @return mixed
     */
    public function getId();

    /**
     * @return string
     */
    public function getNom(): ?string;

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void;

    /**
     * @return string
     */
    public function getValeur(): ?string;

    /**
     * @param string $valeur
     */
    public function setValeur(string $valeur): void;

    /**
     * @return ListingAttributsInterface
     */
    public function getListingAttributs(): ListingAttributsInterface;

    /**
     * @param ListingAttributsInterface $listingAttributs
     */
    public function setListingAttributs(ListingAttributsInterface $listingAttributs): void;


    /**
     * @return PrixInterface|null
     */
    public function getPrix(): ?PrixInterface;

    /**
     * @param PrixInterface $prix
     * @return mixed
     */
    public function setPrix(PrixInterface $prix): void;

    /**
     * @return float|null
     */
    public function getPrixPromoHtva(): ?float;

    /**
     * @return float|null
     */
    public function getPrixHtva(): ?float;

    /**
     * @return float|null
     */
    public function getPrixApplique(): ?float;


}