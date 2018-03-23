<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace App\Entity\InterfaceDef;

use App\Entity\Client\Adresse;
use App\Entity\Commande\CommandeCout;
use App\Entity\Livraison\LieuLivraison;
use App\Entity\Stripe\StripeCharge;

interface CommandeInterface
{
    public function __toString();

    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set user
     *
     * @param string $user
     *
     * @return CommerceInterface
     */
    public function setUser(string $user);

    /**
     * Get user
     *
     * @return string
     */
    public function getUser();

    /**
     * Set commerceNom
     *
     * @param string $commerceNom
     *
     * @return CommerceInterface
     */
    public function setCommerceNom(string $commerceNom);

    /**
     * Get commerceNom
     *
     * @return string
     */
    public function getCommerceNom();

    /**
     * @return bool
     */
    public function isValide(): bool;

    /**
     * @param bool $valide
     */
    public function setValide(bool $valide): void;

    /**
     * @return bool
     */
    public function isCloture(): bool;

    /**
     * @param bool $cloture
     */
    public function setCloture(bool $cloture): void;

    /**
     * @return bool
     */
    public function isPaye(): bool;

    /**
     * @param bool $paye
     */
    public function setPaye(bool $paye): void;

    /**
     * @return bool
     */
    public function isLivre(): bool;

    /**
     * @param bool $livre
     */
    public function setLivre(bool $livre): void;

    /**
     * Set dateLivraison
     *
     * @param \DateTime $dateLivraison
     *
     * @return CommerceInterface
     */
    public function setDateLivraison($dateLivraison);

    /**
     * Get dateLivraison
     *
     * @return \DateTime
     */
    public function getDateLivraison();

    /**
     * Set livraisonRemarque
     *
     * @param string $livraisonRemarque
     *
     * @return CommerceInterface
     */
    public function setLivraisonRemarque(string $livraisonRemarque);

    /**
     * Get livraisonRemarque
     *
     * @return string
     */
    public function getLivraisonRemarque();

    /**
     * Set createdAt
     *
     * @param \DateTime $created
     *
     * @return CommerceInterface
     */
    public function setCreatedAt(\DateTime $created);

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreatedAt();

    /**
     * Set updatedAt
     *
     * @param \DateTime $updated
     *
     * @return CommerceInterface
     */
    public function setUpdatedAt(\DateTime $updated);

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt();

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
     * Get commandeProduits
     *
     * @return CommandeProduitInterface[]
     */
    public function getCommandeProduits();

    /**
     * @return CommandeCout
     */
    public function getCout();

    /**
     * @param CommandeCout $cout
     */
    public function setCout(CommandeCout $cout);

    /**
     * Set lieuLivraison
     *
     * @param \App\Entity\Livraison\LieuLivraison $lieuLivraison
     *
     * @return CommandeInterface
     */
    public function setLieuLivraison(LieuLivraison $lieuLivraison = null);

    /**
     * Get lieuLivraison
     *
     * @return \App\Entity\Livraison\LieuLivraison
     */
    public function getLieuLivraison();

    /**
     * Set valideRemarque
     *
     * @param string $valideRemarque
     *
     * @return CommandeInterface
     */
    public function setValideRemarque(string $valideRemarque);

    /**
     * Get valideRemarque
     *
     * @return string
     */
    public function getValideRemarque();

    /**
     * Set stripeCharge
     *
     * @param \App\Entity\Stripe\StripeCharge $stripeCharge
     *
     * @return CommandeInterface
     */
    public function setStripeCharge(\App\Entity\Stripe\StripeCharge $stripeCharge = null);

    /**
     * Get stripeCharge
     *
     * @return \App\Entity\Stripe\StripeCharge
     */
    public function getStripeCharge() : ?StripeCharge;

    /**
     * @return Adresse|null
     */
    public function getAdresseFacturation(): ?Adresse;

    /**
     * @param Adresse|null $adresse_facturation
     */
    public function setAdresseFacturation(?Adresse $adresse_facturation): void;
}