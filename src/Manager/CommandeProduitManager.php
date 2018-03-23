<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 21:14
 */

namespace App\Manager;

use App\Entity\Security\User;
use App\Entity\Commande\CommandeProduit;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Repository\Commande\CommandeProduitRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeProduitManager extends AbstractManager
{
    private $commandeProduitRepository;
    private $attributManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        AttributManager $attributManager,
        CommandeProduitRepository $commandeProduitRepository
    ) {
        parent::__construct($entityManager);
        $this->commandeProduitRepository = $commandeProduitRepository;
        $this->attributManager = $attributManager;
    }

    public function newInstance(ProduitInterface $produit)
    {
        $commandeProduit = new CommandeProduit();
        $commandeProduit->setProduit($produit);

        return $commandeProduit;
    }

    /**
     * @param ProduitInterface $produit
     * @param CommandeInterface $commande
     * @param integer $quantite
     * @param array $attributs
     * @return CommandeProduit
     */
    public function createCommandeProduit(ProduitInterface $produit, CommandeInterface $commande, $quantite, $attributs)
    {
        $commandeProduit = $this->newInstance($produit);
        $commandeProduit->setCommande($commande);
        $commandeProduit->setQuantite($quantite);

        $this->attributManager->addToCommandeProduit($commandeProduit, $attributs);

        $this->persist($commandeProduit);

        return $commandeProduit;
    }

    /**
     * @param CommandeProduitInterface $commandeProduit
     */
    public function removeCommandeProduit(CommandeProduitInterface $commandeProduit)
    {
        $this->remove($commandeProduit);
        $this->flush();
    }

    /**
     * @param ProduitInterface $produit
     * @param User $user
     * @param array $attributs
     * @return mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getCommandeProduitInPanier(ProduitInterface $produit, User $user, $attributs = [])
    {
        $commerce = $produit->getCommerce();

        return $this->commandeProduitRepository->existInPanier(
            $user,
            $commerce,
            $produit,
            $attributs
        );
    }

    /**
     * @param ProduitInterface $produit
     * @param User|null $user
     * @param array $attributs
     * @return CommandeProduit|mixed|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getOrInitCommandeProduit(ProduitInterface $produit, User $user = null, $attributs = [])
    {
        if(!$user)
            return $commandeProduit = $this->newInstance($produit);

        if (count($produit->getProduitListingsAttributs()) == 0) {

            if ($commandeProduit = $this->getCommandeProduitInPanier($produit, $user, $attributs)) {
                return $commandeProduit;
            }
        }

        return $commandeProduit = $this->newInstance($produit);
    }
}