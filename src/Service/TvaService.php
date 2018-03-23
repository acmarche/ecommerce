<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 22/02/18
 * Time: 13:52
 */

namespace App\Service;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\ProduitInterface;

/**
 * DES QUE LE PRIX EST DEMANDE AVEC LA TVA
 * JUTILISE LA FONCTION GETPRIXAPPLIQUE
 *
 *
 * Class TvaService
 *
 * @package App\Service
 */
class TvaService
{
    protected $paramsUtil;
    protected $prixService;

    public function __construct(ParamsService $paramsUtil, PrixService $prixService)
    {
        $this->paramsUtil = $paramsUtil;
        $this->prixService = $prixService;
    }

    /**
     * Retourne le pourcentage de la tva
     * Tva sur produit ?
     * Tva sur commerce ?
     * Tva sur params
     * @param ProduitInterface $produit
     * @return float
     */
    public function getTvaApplicable(ProduitInterface $produit): float
    {
        if ($produit->getTvaApplicable()) {
            return $produit->getTvaApplicable();
        }

        $commerce = $produit->getCommerce();
        if ($commerce->getTvaApplicable()) {
            return $commerce->getTvaApplicable();
        }

        return $this->paramsUtil->getDefaultTva();
    }

    /**
     * Calcul le montant de la tva d'un produit
     * @param float $prix
     * @param float $tva
     * @return float
     */
    public function calculTva(float $prix, float $tva, bool $rounded = true)
    {
        $montant = $prix * ($tva / 100);
        if ($rounded) {
            return $this->prixService->getRound($montant);
        }

        return $montant;
    }

    /**
     * Retourne le montant de la tva
     * @param ProduitInterface $produit
     * @return mixed
     */
    public function getMontantTvaProduit(ProduitInterface $produit)
    {
        $tva = $this->getTvaApplicable($produit);

        return $this->calculTva($produit->getPrixApplique(), $tva);
    }

    public function getMontantTvaAttribut(AttributInterface $attribut, ProduitInterface $produit)
    {
        $tva = $this->getTvaApplicable($produit);
        return $this->calculTva($attribut->getPrixApplique(), $tva);
    }

    /**
     * Retourne le prix tvac d'un produit
     * @param ProduitInterface $produit
     * @return float
     */
    public function getPrixProduitTvac(ProduitInterface $produit): float
    {
        $pourcentageTva = $this->getTvaApplicable($produit);
        $prixApplique = $produit->getPrixApplique();
        $montantTva = $this->calculTva($prixApplique, $pourcentageTva);

        return $montantTva + $prixApplique;
    }

    /**
     * @param AttributInterface $attribut
     * @param ProduitInterface $produit
     * @return float
     */
    public function getPrixAttributTvac(AttributInterface $attribut, ProduitInterface $produit): float
    {
        $pourcentageTva = $this->getTvaApplicable($produit);
        $prixApplique = $attribut->getPrixApplique();
        $montantTva = $this->calculTva($prixApplique, $pourcentageTva);

        return $montantTva + $prixApplique;
    }

    /**
     * @param ProduitInterface $produit
     * @return float
     */
    public function getPrixPromoTvac(ProduitInterface $produit): float
    {
        $pourcentageTva = $this->getTvaApplicable($produit);
        $montantTva = $this->calculTva($produit->getPrixPromoHtva(), $pourcentageTva);

        return $montantTva + $produit->getPrixPromoHtva();
    }

    /**
     *
     * @param ProduitInterface $produit
     * @param $quantite
     * @return float
     */
    public function getPrixTvacByQuantite(ProduitInterface $produit, $quantite): float
    {
        $prixTvac = $this->getPrixProduitTvac($produit);

        return $quantite * $prixTvac;
    }

    /**
     * @param ProduitInterface $produit
     * @param $quantite
     * @return float
     */
    public function getPrixHtvaByQuantite(ProduitInterface $produit, $quantite): float
    {
        return $quantite * $produit->getPrixHtva();
    }

    public function getPrixTvac(float $prix, float $tva)
    {
        return $prix + $this->calculTva($prix, $tva);
    }

}