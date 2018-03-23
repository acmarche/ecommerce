<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 20/03/18
 * Time: 12:16
 */

namespace App\Service;


use App\Entity\InterfaceDef\ProduitInterface;
use Symfony\Component\HttpFoundation\Request;

class RequestService
{
    /**
     * Lors de l'ajout d'un produit dans le panier
     * Retourne la liste des id des attributs envoyés du form
     *
     * @param Request $request
     * @param ProduitInterface $produit
     * @return array
     */
    public function getAttributs(Request $request, ProduitInterface $produit)
    {
        $attributs = [];
        if (!$commandeAttribut = $request->request->get('commande_produit', false)) {
            return $attributs;
        }

        foreach ($produit->getProduitListingsAttributs() as $produitListing) {
            $key = 'attributs-'.$produitListing->getId();

            if (!$produitListing->isMultiple()) {
                $valeur = $commandeAttribut[$key] ?? false;
                if ($valeur) {
                    $attributs[] = $valeur;
                }
            } else {
                $valeurs = $commandeAttribut[$key] ?? [];

                if (count($valeurs) > 0) {
                    $attributs = array_merge($attributs, $valeurs);
                }
            }
        }
        return $attributs;
    }
}