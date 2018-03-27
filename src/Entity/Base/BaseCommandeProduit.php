<?php

namespace App\Entity\Base;

use Doctrine\ORM\Mapping as ORM;

/**
 * BaseCommandeProduit
 *
 *
 */
abstract class BaseCommandeProduit
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $commentaire;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $produit_nom;

    /**
     * @ORM\Column(type="integer")
     * @var
     */
    protected $quantite;

    /**
     * Pour sauvegarde
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $prixHtva;

    /**
     * Ajouté pour REACT.JS (éviter d'appeler le filter twig)
     * N'existe pas en BD, donc pas d'annotation ORM
     */
    protected $prixTvac;

    /**
     * Pour sauvegarde
     *
     * @var string
     *
     * @ORM\Column(type="decimal", scale=2, nullable=true)
     */
    protected $tvaApplique;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=0} )
     */
    protected $livre = 0;

    public function __toString()
    {
        return "commande produit - ".$this->id;
    }

    /**
     * STOP
     */

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set remarque
     *
     * @param string $commentaire
     *
     * @return BaseCommandeProduit
     */
    public function setCommentaire($commentaire)
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    /**
     * Get remarque
     *
     * @return string
     */
    public function getCommentaire()
    {
        return $this->commentaire;
    }

    /**
     * Set produitNom
     *
     * @param string $produitNom
     *
     * @return BaseCommandeProduit
     */
    public function setProduitNom($produitNom)
    {
        $this->produit_nom = $produitNom;

        return $this;
    }

    /**
     * Get produitNom
     *
     * @return string
     */
    public function getProduitNom()
    {
        return $this->produit_nom;
    }

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return BaseCommandeProduit
     */
    public function setQuantite($quantite)
    {
        $this->quantite = $quantite;

        return $this;
    }

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite()
    {
        return $this->quantite;
    }

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return BaseCommandeProduit
     */
    public function setPrixHtva($prixHtva)
    {
        $this->prixHtva = $prixHtva;

        return $this;
    }

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixHtva()
    {
        return $this->prixHtva;
    }

    /**
     * Set tvaApplique
     *
     * @param string $tvaApplique
     *
     * @return BaseCommandeProduit
     */
    public function setTvaApplique($tvaApplique)
    {
        $this->tvaApplique = $tvaApplique;

        return $this;
    }

    /**
     * Get tvaApplique
     *
     * @return string
     */
    public function getTvaApplique()
    {
        return $this->tvaApplique;
    }

    /**
     * @return bool
     */
    public function isLivre(): bool
    {
        return $this->livre;
    }

    /**
     * @param bool $livre
     */
    public function setLivre(bool $livre): void
    {
        $this->livre = $livre;
    }

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return CommandeProduitInterface
     */
    public function setPrixTvac($prixTvac)
    {
        $this->prixTvac = $prixTvac;
    }

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixTvac()
    {
        return $this->prixTvac;
    }

}
