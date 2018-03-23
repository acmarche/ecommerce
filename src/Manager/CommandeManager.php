<?php
/**
 * Created by PhpStorm.
 * User: jfsenechal
 * Date: 18/03/18
 * Time: 21:54
 */

namespace App\Manager;

use App\Entity\Commande\Commande;
use App\Entity\InterfaceDef\CommandeInterface;
use App\Entity\InterfaceDef\CommerceInterface;
use App\Entity\Security\User;
use App\Repository\Commande\CommandeRepository;
use Doctrine\ORM\EntityManagerInterface;

class CommandeManager extends AbstractManager
{
    private $commandeRepository;

    public function __construct(EntityManagerInterface $entityManager, CommandeRepository $commandeRepository)
    {
        $this->commandeRepository = $commandeRepository;
        parent::__construct($entityManager);
    }

    /**
     * Crée une commande
     *
     * @param CommerceInterface $commerce
     * @param User $user
     * @return CommandeInterface
     */
    public function createCommande(CommerceInterface $commerce, User $user)
    {
        $commande = new Commande();
        $commande->setCommerce($commerce);
        $commande->setUser($user->getUsername());
        $this->persist($commande);

        return $commande;
    }

    /**
     * Je l'ecris deux fois pour pas rassembler manager et repository
     * @param User|null $user
     * @param CommerceInterface $commerce
     * @return Commande[]|null
     */
    public function commandeExistPanier(CommerceInterface $commerce, User $user = null)
    {
        return $this->commandeRepository->commandeExistPanier($user, $commerce);
    }

    public function getOrCreateCommande(CommerceInterface $commerce, User $user = null)
    {
        if (!$commande = $this->commandeExistPanier($commerce, $user)) {
            $commande = $this->createCommande($commerce, $user);
        }

        return $commande;
    }

}