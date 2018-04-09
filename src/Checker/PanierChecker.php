<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 19:52
 */

namespace App\Checker;

use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Manager\PanierManager;

class PanierChecker
{
    private $produitChecker;
    private $quantiteChecker;
    private $attributChecker;
    /**
     * @var PanierManager
     */
    private $panierManager;

    public function __construct(
        ProduitChecker $produitService,
        QuantiteChecker $quantiteChecker,
        AttributChecker $attributChecker
    ) {
        $this->produitChecker = $produitService;
        $this->quantiteChecker = $quantiteChecker;
        $this->attributChecker = $attributChecker;
    }

    /**
     * J'ai fait car ServiceCircularReferenceException
     * @required
     * @param PanierManager $panierManager
     */
    public function setPanierManager(PanierManager $panierManager)
    {
        $this->panierManager = $panierManager;
    }

    /**
     * @param ProduitInterface $produit
     * @param $quantite
     * @param $attributs
     * @throws \Exception
     */
    public function checkToAdd(ProduitInterface $produit, $quantite, $attributs)
    {
        $this->quantiteChecker->valueQuantiteIsReal($quantite);
        $this->checkStock($produit);
        //$this->checkAttribut($attributs);
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     * @param $quantite
     * @param $attributs
     * @throws \Exception
     */
    public function checkToUpdate(CommandeProduitInterface $commandeProduit, $quantite, $attributs)
    {
        $this->quantiteChecker->valueQuantiteIsReal($quantite);
        $this->checkStock($commandeProduit->getProduit());
    }

    public function checkToDelete(CommandeProduitInterface $commandeProduit)
    {

    }

    public function checkToDeleteAttribut(AttributInterface $attribut)
    {

    }



    /**
     * @param ProduitInterface $produit
     * @throws \Exception
     */
    public function checkStock(ProduitInterface $produit)
    {
        if (!$this->produitChecker->checkStock($produit)) {
            throw new \Exception('Plus de stock disponible pour '.$produit);
        }
    }

    /**
     * @param $quantite
     * @throws \Exception
     */
    public function checkQuantite($quantite)
    {
        if (!$this->quantiteChecker->valueQuantiteIsReal($quantite)) {
            throw new \Exception('Quantité minimum = 1');
        }
    }


    /**
     *
     * Lorsqu'on va sur la page index du panier
     * check panier vide
     * check produit rupture stok
     * check produit non disponible
     * clean commande sans produit
     *
     * @param CommandeInterface[] $commandes
     * @return array
     * @throws \Exception
     */
    public function checkAllPanier($commandes)
    {
        foreach ($commandes as $commande) {
            foreach ($commande->getCommandeProduits() as $commandeProduit) {

                $produit = $commandeProduit->getProduit();

                $this->checkStock($produit);

                if (!$this->produitChecker->checkDisponible($produit)) {
                    throw new \Exception('Ce produit n\'est plus disponible : '.$produit);
                }
            }
        }

        $this->panierManager->cleanCommandeWithoutProduit($commandes);

        //refresh panier
        return $this->panierManager->getPanierEncours();
    }

    public function checkToAddAttribute($attribut)
    {

    }
}