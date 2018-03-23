<?php

namespace App\Entity\Attribut;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ListingAttributsInterface;
use App\Entity\TraitDef\PrixTrait;
use App\Entity\TraitDef\QuantiteTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\Attribut\AttributRepository")
 */
class Attribut implements AttributInterface
{
    use PrixTrait;
    use QuantiteTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @var string
     */
    private $nom;

    /**
     * clef ?
     * @ORM\Column(type="string", length=30, nullable=true)
     * @var string
     */
    private $valeur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Attribut\ListingAttributs", inversedBy="attributs")
     * @ORM\JoinColumn(nullable=false)
     * @var ListingAttributs
     */
    private $listingAttributs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Commande\CommandeProduit", mappedBy="attributs")
     * @var CommandeProduitInterface
     */
    private $commande_produit;

    public function __construct()
    {
        $this->commande_produit = new ArrayCollection();
        $this->listingAttributs = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->nom;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    /**
     * @param string $valeur
     */
    public function setValeur(string $valeur): void
    {
        $this->valeur = $valeur;
    }

    /**
     * @return ListingAttributsInterface
     */
    public function getListingAttributs(): ListingAttributsInterface
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

}
