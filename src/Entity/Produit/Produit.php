<?php

namespace App\Entity\Produit;

use App\Entity\Base\BaseProduit;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\TraitDef\CategoryTrait;
use App\Entity\TraitDef\CommandeProduitTrait;
use App\Entity\TraitDef\CommerceTrait;
use App\Entity\TraitDef\PrixTrait;
use App\Entity\TraitDef\ProduitListingAttributTrait;
use App\Entity\TraitDef\QuantiteTrait;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * produit
 *
 * @ORM\Table(name="produit")
 * @ORM\Entity(repositoryClass="App\Repository\Produit\ProduitRepository")
 *
 */
class Produit extends BaseProduit implements ProduitInterface, JsonSerializable
{
    use CategoryTrait;
    use CommerceTrait;
    use CommandeProduitTrait;
    use ProduitListingAttributTrait;
    use PrixTrait;
    use QuantiteTrait;

    /**
     * Surcharge pour mappedBy
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Commande\CommandeProduit", mappedBy="produit", cascade={"persist", "remove"})
     *
     */
    protected $commande_produits;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lunch\Supplement", inversedBy="produits", cascade={"persist", "detach"})
     *
     */
    protected $supplements;
    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lunch\Ingredient", inversedBy="produits", cascade={"persist", "detach"})
     *
     */
    protected $ingredients;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Produit\ProduitImage", mappedBy="produit", cascade={"persist", "remove"})
     * @ORM\OrderBy({"position": "ASC"})
     */
    protected $images;

    /**
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Produit\ProduitDimension", mappedBy="produit", cascade={"persist", "remove"})
     *
     */
    protected $dimension;

    public function getFirstImage(): ?ProduitImage
    {
        return isset($this->images[0]) ? $this->images[0] : null;
    }

    /**
     * STOP
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commande_produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->produitListingsAttributs = new \Doctrine\Common\Collections\ArrayCollection();
        $this->supplements = new \Doctrine\Common\Collections\ArrayCollection();
        $this->ingredients = new \Doctrine\Common\Collections\ArrayCollection();
        $this->images = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add supplement
     *
     * @param \App\Entity\Lunch\Supplement $supplement
     *
     * @return Produit
     */
    public function addSupplement(\App\Entity\Lunch\Supplement $supplement)
    {
        $this->supplements[] = $supplement;

        return $this;
    }

    /**
     * Remove supplement
     *
     * @param \App\Entity\Lunch\Supplement $supplement
     */
    public function removeSupplement(\App\Entity\Lunch\Supplement $supplement)
    {
        $this->supplements->removeElement($supplement);
    }

    /**
     * Get supplements
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getSupplements()
    {
        return $this->supplements;
    }

    /**
     * Add ingredient
     *
     * @param \App\Entity\Lunch\Ingredient $ingredient
     *
     * @return Produit
     */
    public function addIngredient(\App\Entity\Lunch\Ingredient $ingredient)
    {
        $this->ingredients[] = $ingredient;

        return $this;
    }

    /**
     * Remove ingredient
     *
     * @param \App\Entity\Lunch\Ingredient $ingredient
     */
    public function removeIngredient(\App\Entity\Lunch\Ingredient $ingredient)
    {
        $this->ingredients->removeElement($ingredient);
    }

    /**
     * Get ingredients
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIngredients()
    {
        return $this->ingredients;
    }

    /**
     * Add image
     *
     * @param \App\Entity\Produit\ProduitImage $image
     *
     * @return Produit
     */
    public function addImage(\App\Entity\Produit\ProduitImage $image)
    {
        $this->images[] = $image;

        return $this;
    }

    /**
     * Remove image
     *
     * @param \App\Entity\Produit\ProduitImage $image
     */
    public function removeImage(\App\Entity\Produit\ProduitImage $image)
    {
        $this->images->removeElement($image);
    }

    /**
     * Get images
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getImages()
    {
        return $this->images;
    }

    /**
     * Set dimension
     *
     * @param \App\Entity\Produit\ProduitDimension $dimension
     *
     * @return Produit
     */
    public function setDimension(\App\Entity\Produit\ProduitDimension $dimension)
    {
        $this->dimension = $dimension;

        return $this;
    }

    /**
     * Get dimension
     *
     * @return \App\Entity\Produit\ProduitDimension
     */
    public function getDimension()
    {
        return $this->dimension;
    }

    /**
     * Raccourcis
     * @return float|int|null
     */
    public function getPrixPromoHtva(): ?float
    {
        if ($this->getPrix()) {
            return $this->getPrix()->getPromoHtva();
        }

        return 0;
    }

    /**
     * Raccourcis
     * @return float|int|null
     */
    public function getPrixHtva(): ?float
    {
        if ($this->getPrix()) {
            return $this->getPrix()->getHtva();
        }

        return 0;
    }

    /**
     * @return float|null
     */
    public function getPrixApplique(): ?float
    {
        if ($this->getPrixPromoHtva()) {
            return $this->getPrixPromoHtva();
        }

        return $this->getPrixHtva();
    }

    public function jsonSerialize()
    {
        //Les collections Doctrines sont chargées de manière lazy,
        //Une array est créée pour sérialiser les objets de manière forcée
        $arrayAttributs = array();
        foreach ($this->getProduitListingsAttributs() as $attribut) {
            array_push($arrayAttributs, $attribut);
        }

        return[
            'id'=>$this->getId(),
            'nom' =>$this->getNom(),
            'produitListingAttributs' => $arrayAttributs
        ];
    }
}
