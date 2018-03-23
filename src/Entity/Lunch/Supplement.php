<?php

namespace App\Entity\Lunch;

use App\Entity\Base\BaseEntity;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Supplements
 *
 * @ORM\Table(name="supplement")
 * @ORM\Entity(repositoryClass="App\Repository\Lunch\SupplementRepository")
 */
class Supplement extends BaseEntity
{
    /**
     * @var float
     *
     * @ORM\Column(type="decimal", scale=2)
     */
    protected $prix;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $indisponible = false;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $rupture_stock = false;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Produit\Produit", mappedBy="supplements")
     *
     * @var
     */
    protected $produits;

    /**
     * Override pour inversedBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Commerce\Commerce", inversedBy="supplements")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande\CommandeProduit", mappedBy="supplements")
     * @var CommandeProduitInterface
     */
    private $commande_produit;

    public function __toString()
    {
        return $this->nom.' (+'.$this->prix.' €)';
    }

    /**
     * STOP
     */

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->produits = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Set prix
     *
     * @param string $prix
     *
     * @return Supplement
     */
    public function setPrix($prix)
    {
        $this->prix = $prix;

        return $this;
    }

    /**
     * Get prix
     *
     * @return string
     */
    public function getPrix()
    {
        return $this->prix;
    }

    /**
     * Set indisponible
     *
     * @param boolean $indisponible
     *
     * @return Supplement
     */
    public function setIndisponible($indisponible)
    {
        $this->indisponible = $indisponible;

        return $this;
    }

    /**
     * Get indisponible
     *
     * @return boolean
     */
    public function getIndisponible()
    {
        return $this->indisponible;
    }

    /**
     * Set ruptureStock
     *
     * @param boolean $ruptureStock
     *
     * @return Supplement
     */
    public function setRuptureStock($ruptureStock)
    {
        $this->rupture_stock = $ruptureStock;

        return $this;
    }

    /**
     * Get ruptureStock
     *
     * @return boolean
     */
    public function getRuptureStock()
    {
        return $this->rupture_stock;
    }

    /**
     * Add produit
     *
     * @param \App\Entity\Produit\Produit $produit
     *
     * @return Supplement
     */
    public function addProduit(\App\Entity\Produit\Produit $produit)
    {
        $this->produits[] = $produit;

        return $this;
    }

    /**
     * Remove produit
     *
     * @param \App\Entity\Produit\Produit $produit
     */
    public function removeProduit(\App\Entity\Produit\Produit $produit)
    {
        $this->produits->removeElement($produit);
    }

    /**
     * Get produits
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProduits()
    {
        return $this->produits;
    }

    /**
     * Set commerce
     *
     * @param \App\Entity\Commerce\Commerce $commerce
     *
     * @return Supplement
     */
    public function setCommerce(\App\Entity\Commerce\Commerce $commerce)
    {
        $this->commerce = $commerce;

        return $this;
    }

    /**
     * Get commerce
     *
     * @return \App\Entity\Commerce\Commerce
     */
    public function getCommerce()
    {
        return $this->commerce;
    }
}
