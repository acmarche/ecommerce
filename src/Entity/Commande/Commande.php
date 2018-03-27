<?php

namespace App\Entity\Commande;

use App\Entity\Base\BaseCommande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\Stripe\StripeCharge;
use App\Entity\TraitDef\AdresseFacturationTrait;
use App\Entity\TraitDef\CommandeProduitTrait;
use App\Entity\TraitDef\CommerceTrait;
use App\Entity\TraitDef\LieuLivraisonTrait;
use App\Entity\TraitDef\UuidTrait;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use JsonSerializable;
use Ramsey\Uuid\Uuid;

/**
 * Commande
 *
 * @ORM\Table(name="commande")
 * @ORM\Entity(repositoryClass="App\Repository\Commande\CommandeRepository")
 */
class Commande extends BaseCommande implements CommandeInterface, JsonSerializable
{
    use CommerceTrait;
    use LieuLivraisonTrait;
    use AdresseFacturationTrait;
    use CommandeProduitTrait;
    use TimestampableEntity;
    use UuidTrait;

    /**
     * Overload pour le inversedBy
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Commerce\Commerce", inversedBy="commandes")
     * @ORM\JoinColumn(nullable=false)
     * @var
     */
    protected $commerce;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Stripe\StripeCharge")
     *
     */
    protected $stripe_charge;

    /**
     * Get isFood
     * Raccourcis pour les templates
     * @return boolean
     */
    public function getisFood()
    {
        return false;
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->commande_produits = new \Doctrine\Common\Collections\ArrayCollection();
        $this->uuid = Uuid::uuid4();
    }

    /**
     * STOP
     */

    /**
     * Set stripeCharge
     *
     * @param \App\Entity\Stripe\StripeCharge $stripeCharge
     *
     * @return Commande
     */
    public function setStripeCharge(\App\Entity\Stripe\StripeCharge $stripeCharge = null)
    {
        $this->stripe_charge = $stripeCharge;

        return $this;
    }

    /**
     * Get stripeCharge
     *
     * @return \App\Entity\Stripe\StripeCharge
     */
    public function getStripeCharge(): ?StripeCharge
    {
        return $this->stripe_charge;
    }

    public function jsonSerialize()
    {
        $arrayProduits = array();
        foreach ($this->getCommandeProduits() as $produit) {
            array_push($arrayProduits, $produit);
        }

        return [
            'id' => $this->getId(),
            'commerce' => $this->getCommerce(),
            'produits' => $arrayProduits,
            'cout' => $this->getCout()
        ];
    }
}
