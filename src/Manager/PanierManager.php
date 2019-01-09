<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 19:36
 */

namespace App\Manager;

use App\Checker\PanierChecker;
use App\Entity\Commande\Commande;
use App\Entity\Commande\CommandeProduit;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Security\User;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;

class PanierManager extends AbstractManager
{
    private $panierChecker;
    private $userService;
    private $currentUser;
    private $commandeManager;
    private $commandeProduitManger;
    private $attributManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserService $userService,
        PanierChecker $panierChecker,
        CommandeManager $commandeManager,
        CommandeProduitManager $commandeProduitManager,
        AttributManager $attributManager
    ) {
        $this->panierChecker = $panierChecker;
        $this->userService = $userService;
        $this->commandeManager = $commandeManager;
        $this->commandeProduitManger = $commandeProduitManager;
        $this->attributManager = $attributManager;
        parent::__construct($entityManager);
    }

    /**
     * @required
     * @param User|null $user
     */
    public function setCurrentUser(User $user = null)
    {
        if (!$user) {
            $user = $this->userService->getCurrentUser();
        }
        $this->currentUser = $user;
    }

    /**
     * @return CommandeInterface[]
     */
    public function getPanierEncours()
    {
        return $this->entityManager->getRepository(Commande::class)->getPanier($this->currentUser);
    }

    /**
     * @param ProduitInterface $produit
     * @param $quantite
     * @param array $attributs tableau d'ids
     * @return CommandeProduit|CommandeProduitInterface|null
     * @throws \Exception
     */
    public function addProduit(ProduitInterface $produit, $quantite, array $attributs = [])
    {
        $commerce = $produit->getCommerce();

        $this->panierChecker->checkToAdd($produit, $quantite, $attributs);

        $commande = $this->commandeManager->getOrCreateCommande($commerce, $this->currentUser);

        if ($commandeProduit = $this->commandeProduitManger->getCommandeProduitInPanier(
            $produit,
            $this->currentUser,
            $attributs
        )) {
            $quantite = $commandeProduit->getQuantite() + $quantite;
        } else {
            $commandeProduit = $this->commandeProduitManger->createCommandeProduit(
                $produit,
                $commande,
                $quantite,
                $attributs
            );
        }

        $commandeProduit->setQuantite($quantite);
        $this->flush();

        return $commandeProduit;
    }


    /**
     * UPDATE DEPUIS LE PANIER
     * @param CommandeProduitInterface $commandeProduit
     * @param $quantite
     * @param array $attributs issu du form
     * @return CommandeProduitInterface
     * @throws \Exception
     */
    public function updateProduit(CommandeProduitInterface $commandeProduit, $quantite, $attributs = [])
    {
        $this->panierChecker->checkToUpdate($commandeProduit, $quantite, $attributs);

        /*   //si commandeProduit deja dans le panier
           if (count($attributs) == 0) {
               $this->attributManager->addToCommandeProduit($commandeProduit, $attributs);
           }*/

        $commandeProduit->setQuantite($quantite);

        $this->flush();

        return $commandeProduit;
    }

    public function removeProduit(CommandeProduitInterface $commandeProduit)
    {
        $this->panierChecker->checkToDelete($commandeProduit);
        $this->commandeProduitManger->removeCommandeProduit($commandeProduit);
    }

    public function addAttribut(CommandeProduitInterface $commandeProduit, AttributInterface $attribut){
        $this->panierChecker->checkToAddAttribute($attribut);
        $commandeProduit->addAttribut($attribut);
        $this->flush();
    }

    public function removeAttribut(CommandeProduitInterface $commandeProduit, AttributInterface $attribut)
    {
        $this->panierChecker->checkToDeleteAttribut($attribut);
        $commandeProduit->removeAttribut($attribut);
        $this->flush();
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     * @param null|string $commentaire
     * @return CommandeProduitInterface
     */
    public function commentaireProduit(CommandeProduitInterface $commandeProduit, ?string $commentaire)
    {
        $commandeProduit->setCommentaire($commentaire);
        $this->flush();

        return $commandeProduit;
    }


    /**
     * Vider le panier
     */
    public function clean()
    {
        $commandes = $this->getPanierEncours();
        foreach ($commandes as $commande) {
            foreach ($commande->getCommandeProduits() as $commandeProduit) {
                $this->remove($commandeProduit);
            }
            $this->remove($commande);
        }
        $this->flush();
    }

    /**
     * Lorsqu'on va sur le panier, toute commande sans produit est supprimee
     * @param $commandes CommandeInterface[]
     * @return CommandeInterface[]
     */
    public function cleanCommandeWithoutProduit($commandes)
    {
        foreach ($commandes as $key => $commande) {
            if (count($commande->getCommandeProduits()) < 1) {
                $this->remove($commande);
                unset($commandes[$key]);
            }
        }

        $this->flush();

        return $commandes;
    }
}