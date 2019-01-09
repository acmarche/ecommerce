<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 15/09/17
 * Time: 17:02
 */

namespace App\Entity\InterfaceDef;


interface CommandeProduitInterface
{
    /**
     * Get id
     *
     * @return integer
     */
    public function getId();

    /**
     * Set commande
     *
     * @param CommandeInterface $commande
     *
     */
    public function setCommande(CommandeInterface $commande);

    /**
     * Get commerce
     *
     * @return CommandeInterface
     */
    public function getCommande();

    /**
     * @return ProduitInterface
     */
    public function getProduit();

    /**
     * @param ProduitInterface $produit
     */
    public function setProduit(ProduitInterface $produit);


    /**
     * Set commentaire
     *
     * @param string $commentaire
     *
     * @return CommandeProduitInterface
     */
    public function setCommentaire($commentaire);

    /**
     * Get commentaire
     *
     * @return string
     */
    public function getCommentaire();

    /**
     * Set produitNom
     *
     * @param string $produitNom
     *
     * @return CommandeProduitInterface
     */
    public function setProduitNom($produitNom);

    /**
     * Get produitNom
     *
     * @return string
     */
    public function getProduitNom();

    /**
     * Set quantite
     *
     * @param integer $quantite
     *
     * @return CommandeProduitInterface
     */
    public function setQuantite($quantite);

    /**
     * Get quantite
     *
     * @return integer
     */
    public function getQuantite();

    /**
     * Set prixHtva
     *
     * @param string $prixHtva
     *
     * @return CommandeProduitInterface
     */
    public function setPrixHtva($prixHtva);

    /**
     * Get prixHtva
     *
     * @return string
     */
    public function getPrixHtva();

    /**
     * Set prixTvac
     *
     * @param string $prixHtva
     *
     * @return CommandeProduitInterface
     */
    public function setPrixTvac($prixHtva);

    /**
     * Get prixTvac
     *
     * @return string
     */
    public function getPrixTvac();



    /**
     * Set tvaApplique
     *
     * @param string $tvaApplique
     *
     * @return CommandeProduitInterface
     */
    public function setTvaApplique($tvaApplique);

    /**
     * Get tvaApplique
     *
     * @return string
     */
    public function getTvaApplique();

    /**
     * @return AttributInterface[]
     */
    public function getAttributs();

    /**
     * @param AttributInterface $attribut
     * @return mixed
     */
    public function addAttribut(AttributInterface $attribut);

    public function removeAttribut(AttributInterface $attribut);


}