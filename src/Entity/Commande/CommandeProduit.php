<?php

namespace App\Entity\Commande;

use App\Entity\Attribut\Attribut;
use App\Entity\Base\BaseCommandeProduit;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\Lunch\Supplement;
use App\Entity\TraitDef\ProduitTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;

/**
 * CommandeProduit
 *
 * @ORM\Table(name="commande_produit")
 * @ORM\Entity(repositoryClass="App\Repository\Commande\CommandeProduitRepository")
 */
class CommandeProduit extends BaseCommandeProduit implements CommandeProduitInterface, JsonSerializable
{
    use ProduitTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Commande\Commande", inversedBy="commande_produits")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commande;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Attribut\Attribut", inversedBy="commande_produit")
     * @ORM\JoinColumn(nullable=false)
     * @var Attribut[]
     */
    protected $attributs;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Lunch\Supplement", inversedBy="commande_produit")
     * @ORM\JoinColumn(nullable=false)
     * @var Supplement[]
     */
    protected $supplements;



    public function __construct()
    {
        $this->attributs = new ArrayCollection();
        $this->supplements = new ArrayCollection();
    }


    /**
     * Set commande
     *
     * @param CommandeInterface $commande
     *
     */
    public function setCommande(CommandeInterface $commande)
    {
        $this->commande = $commande;
    }

    /**
     * Get commerce
     *
     * @return CommandeInterface
     */
    public function getCommande(): CommandeInterface
    {
        return $this->commande;
    }

    /**
     * @return Attribut[]
     */
    public function getAttributs()
    {
        return $this->attributs;
    }

    public function addAttribut(AttributInterface $attribut)
    {
        // for a many-to-many association:
       // $attribut->addCommandeProduit($this);

        // for a many-to-one association:
        //$attribut->setListingAttributs($this);

        $this->attributs->add($attribut);
    }

    public function removeAttribut(AttributInterface $attribut)
    {
        $this->attributs->removeElement($attribut);
    }

    /**
     * @param Attribut[] $attributs
     */
    public function setAttributs(array $attributs): void
    {
        $this->attributs = $attributs;
    }

    /**
     * @return Supplement[]
     */
    public function getSupplements(): array
    {
        return $this->supplements;
    }

    /**
     * @param Supplement[] $supplements
     *
     */
    public function setSupplements(array $supplements): void
    {
        $this->supplements = $supplements;
    }

    public function jsonSerialize()
    {

        //Les collections Doctrines sont chargées de manière lazy,
        //Une array est créée pour sérialiser les objets de manière forcée
        $arrayAttributs = array();
        foreach ($this->getAttributs() as $attribut) {
            array_push($arrayAttributs, $attribut);
        }

        return [
            'id' => $this->getId(),
            'produit' =>$this->getProduit(),
            'quantite' =>$this->getQuantite(),
            'prixTvac' => $this->getPrixTvac(),
            'prixHtva' => $this->getPrixHtva(),
            'idCommande' => $this->getCommande()->getId(),
            'attributs'=> $arrayAttributs
        ];

    }


}
