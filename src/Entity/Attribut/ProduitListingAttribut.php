<?php

namespace App\Entity\Attribut;

use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\InterfaceDef\ProduitListingInterface;
use App\Entity\Produit\Produit;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Attribut\ProduitListingAttributRepository")
 * @UniqueEntity(fields={"produit","listingAttributs"}, message="Une liste ne peut être présente deux fois")
 */
class ProduitListingAttribut implements ProduitListingInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Produit\Produit", inversedBy="produitListingsAttributs")
     * @ORM\JoinColumn(nullable=false)
     * @var Produit
     */
    private $produit;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Attribut\ListingAttributs")
     * @ORM\JoinColumn(nullable=false)
     * @var ListingAttributs
     */
    private $listingAttributs;

    /**
     * Attribut obligatoire ?
     * @ORM\Column(type="boolean", options={"default"=0} )
     * @var boolean
     */
    private $required;

    /**
     * Attribut en select ou en radio
     * @ORM\Column(type="boolean", options={"default"=0} )
     * @var boolean
     */
    private $multiple;

    /**
     * Attribut en select ou en checkbox
     * @ORM\Column(type="boolean", options={"default"=0} )
     * @var boolean
     */
    private $expand;

    public function __construct()
    {
        $this->required = false;
        $this->multiple = false;
        $this->expand = false;
    }

    public static function create(
        Produit $produit,
        ListingAttributs $listingAttributs,
        bool $required,
        bool $multiple,
        bool $expand
    ) {
        $t = new self();
        $t->produit = $produit;
        $t->listingAttributs = $listingAttributs;
        $t->required = $required;
        $t->multiple = $multiple;
        $t->expand = $expand;

        return $t;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return ProduitInterface
     */
    public function getProduit(): ProduitInterface
    {
        return $this->produit;
    }

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit): void
    {
        $this->produit = $produit;
    }

    /**
     * @return ListingAttributsInterface
     */
    public function getListingAttributs(): ?ListingAttributsInterface
    {
        return $this->listingAttributs;
    }

    /**
     * @param ListingAttributsInterface $listingAttributs
     */
    public function setListingAttributs(ListingAttributsInterface $listingAttributs): void
    {
        $this->listingAttributs = $listingAttributs;
    }

    /**
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     */
    public function setRequired(bool $required): void
    {
        $this->required = $required;
    }

    /**
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->multiple;
    }

    /**
     * @param bool $multiple
     */
    public function setMultiple(bool $multiple): void
    {
        $this->multiple = $multiple;
    }

    /**
     * @return bool
     */
    public function isExpand(): bool
    {
        return $this->expand;
    }

    /**
     * @param bool $expand
     */
    public function setExpand(bool $expand): void
    {
        $this->expand = $expand;
    }

}
