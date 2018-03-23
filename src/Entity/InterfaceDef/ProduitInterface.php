<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace App\Entity\InterfaceDef;


use App\Entity\Attribut\ProduitListingAttribut;

interface ProduitInterface
{
    public function __toString();

    /**
     * @return int
     */
    public function getId();

    /**
     * @param int $id
     */
    public function setId($id);

    /**
     * @return string
     */
    public function getNom();

    /**
     * @param string $nom
     */
    public function setNom($nom);

    /**
     * @return mixed
     */
    public function getCreatedAt();

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt(\DateTime $created);

    /**
     * @return mixed
     */
    public function getUpdatedAt();

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt(\DateTime $updated);

    /**
     * Set reference
     *
     * @param string $reference
     *
     * @return ProduitInterface
     */
    public function setReference($reference);

    /**
     * Get reference
     *
     * @return string
     */
    public function getReference();

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ProduitInterface
     */
    public function setDescription($description);

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription();

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return ProduitInterface
     */
    public function setIndisponible($indisponible);

    /**
     * Get indisponible
     *
     * @return boolean
     */
    public function getIndisponible();

    /**
     * Set quantiteStock
     *
     * @param integer $quantiteStock
     *
     * @return ProduitInterface
     */
    public function setQuantiteStock($quantiteStock);

    /**
     * Get quantiteStock
     *
     * @return integer
     */
    public function getQuantiteStock();

    /**
     * @return bool
     */
    public function isDelai24h(): bool;

    /**
     * @param bool $delai_24h
     */
    public function setDelai24h(bool $delai_24h): void;

    /**
     * Set tvaApplicable
     *
     * @param string $tvaApplicable
     *
     * @return ProduitInterface
     */
    public function setTvaApplicable($tvaApplicable);

    /**
     * Get tvaApplicable
     *
     * @return string
     */
    public function getTvaApplicable();

    /**
     * Set commerce
     *
     * @param CategoryInterface $commerce
     */
    public function setCommerce(CommerceInterface $commerce);

    /**
     * Get commerce
     *
     * @return CommerceInterface
     */
    public function getCommerce();

    /**
     * Set isFood
     *
     * @param boolean $isFood
     *
     * @return ProduitInterface
     */
    public function setIsFood($isFood);

    /**
     * Get isFood
     *
     * @return boolean
     */
    public function getIsFood();

    /**
     * Get isOutOfStock
     * @return boolean
     */
    public function isOutOfStock();

    public function getFirstImage();

    /**
     * @return ProduitListingAttribut[]
     */
    public function getProduitListingsAttributs();

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