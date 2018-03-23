<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 22/02/18
 * Time: 13:56
 */

namespace App\Service;

use App\Entity\Commande\CommandeCout;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CommandeCoutService
{
    private $manager;
    private $tvaService;
    private $supplementUtil;
    private $stripeService;
    private $prixService;

    function __construct(
        ObjectManager $manager,
        TvaService $tvaService,
        SupplementsUtil $supplementsUtil,
        StripeService $stripeService,
        PrixService $prixService
    ) {
        $this->manager = $manager;
        $this->tvaService = $tvaService;
        $this->stripeService = $stripeService;
        $this->supplementUtil = $supplementsUtil;
        $this->prixService = $prixService;
    }

    /**
     * @param $commandes CommandeInterface[]
     */
    public function bindCouts($commandes)
    {
        foreach ($commandes as $commande) {
            $commande->setCout($this->getCoutsCommande($commande));
        }
    }

    /**
     * @param CommandeInterface $commande
     * @return CommandeCout
     */
    public function getCoutsCommande(CommandeInterface $commande)
    {
        $cout = new CommandeCout();
        $cout->setMontantTva($this->calculerMontantTvaCommande($commande));
        $cout->setTotalHtva($this->calculerTotalCommandeHtva($commande));
        $cout->setTotalTvac($this->getTotalTvacCommande($commande));
        $cout->setAttributsHtva($this->getTotalHtvaAttributs($commande));
        $cout->setAttributsTvac($this->getTvaAttributs($commande));
        $cout->setFraisTransaction($this->stripeService->calculFraisTransaction($cout->getTotalTvac()));
        $cout->setAPayer($cout->getTotalTvac() + $cout->getFraisTransaction());
        $cout->setTotalInCents($this->prixService->getInCent($cout->getTotalTvac()));

        return $cout;
    }

    /**
     * Tva sur chaques produits
     *
     * @param CommandeInterface $commande
     * @return float
     */
    public function calculerMontantTvaCommande(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $montantTva = $this->tvaService->getMontantTvaProduit($produit);
            $total += $montantTva * $commandeProduit->getQuantite();
            foreach ($commandeProduit->getAttributs() as $attribut) {
                $total += $this->tvaService->getMontantTvaAttribut($attribut, $produit);
            }
        }

        $total += $this->getTvaAttributs($commande);

        return $total;
    }

    /**
     * Total de la commande sans la tva
     * @param CommandeInterface $commande
     * @return float
     */
    public function calculerTotalCommandeHtva(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $total += $this->tvaService->getPrixHtvaByQuantite($produit, $commandeProduit->getQuantite());
        }

        $total += $this->getTotalHtvaAttributs($commande);

        return $total;
    }

    /**
     * Total de la commande avec la tva
     * @param CommandeInterface $commande
     * @return float
     */
    public function getTotalTvacCommande(CommandeInterface $commande)
    {
        $commandeProduits = $commande->getCommandeProduits();
        $total = 0;
        foreach ($commandeProduits as $commandeProduit) {
            $produit = $commandeProduit->getProduit();
            $total += $this->tvaService->getPrixTvacByQuantite($produit, $commandeProduit->getQuantite());
        }
        $total += ($this->getTotalHtvaAttributs($commande) + $this->getTvaAttributs($commande));

        return $total;
    }

    /**
     * Le montant total de la tva des attribut
     * @param CommandeInterface $commande
     * @return float|int
     */
    public function getTvaAttributs(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {

            $produit = $commandeProduit->getProduit();
            foreach ($commandeProduit->getAttributs() as $attribut) {
                $total += $this->tvaService->getMontantTvaAttribut($attribut, $produit);
            }
        }

        return $total;
    }

    /**
     * Total des attributs sans la tva
     * @param CommandeInterface $commande
     * @return float|int|null
     */
    public function getTotalHtvaAttributs(CommandeInterface $commande)
    {
        $total = 0;
        foreach ($commande->getCommandeProduits() as $commandeProduit) {
            foreach ($commandeProduit->getAttributs() as $attribut) {
                $total += $attribut->getPrixApplique();
            }
        }

        return $total;
    }
}