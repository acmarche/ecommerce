<?php

namespace App\Repository\Commande;

use App\Entity\Commande\CommandeProduit;
use App\Entity\InterfaceDef\AttributInterface;
use App\Entity\InterfaceDef\CommandeProduitInterface;
use App\Entity\InterfaceDef\CommerceInterface;
use App\Entity\InterfaceDef\ProduitInterface;
use App\Entity\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CommandeProduit|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeProduit|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeProduit[]    findAll()
 * @method CommandeProduit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CommandeProduit::class);
    }

    /**
     *
     * Check si la commande produit existe dans le panier
     *
     * User = null pour non connecte
     *
     * @param User|null $user
     * @param CommerceInterface $commerce
     * @param ProduitInterface $produit
     * @param array $attributs
     * @return mixed|null
     * @throws NonUniqueResultException
     */
    public function existInPanier(
        User $user = null,
        CommerceInterface $commerce,
        ProduitInterface $produit,
        $attributs = []
    ) {
        if (!$user) {
            return null;
        }

        $qb = $this->createQueryBuilder('commandeProduit');
        $qb->leftJoin('commandeProduit.commande', 'commande', 'WITH');
        $qb->leftJoin('commandeProduit.produit', 'produit', 'WITH');
        $qb->leftJoin('commandeProduit.attributs', 'attributs', 'WITH');
        $qb->leftJoin('commande.commerce', 'commerce', 'WITH');
        $qb->addSelect('commande', 'commerce', 'produit', 'attributs');

        $qb->andWhere('commande.user = :username')
            ->setParameter('username', $user->getUsername());

        $qb->andWhere('produit = :produit')
            ->setParameter('produit', $produit);

        $qb->andWhere('commerce = :commerce')
            ->setParameter('commerce', $commerce);

        $qb->andWhere('commande.paye = :paye')
            ->setParameter('paye', 0);

        if (count($attributs) > 0) {
            $qb->andWhere('attributs IN (:attributs)')
                ->setParameter('attributs', $attributs);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }

    /**
     * Utiliser lors de l'affichage d'un produit
     * Je peux afficher si le produit est present ou pas dans le panier
     * @param ProduitInterface $produit
     * @param User $user
     * @return mixed
     */
    public function getCommandesProduitByProduitInPanier(ProduitInterface $produit, User $user)
    {
        $qb = $this->createQueryBuilder('commandeProduit');
        $qb->leftJoin('commandeProduit.commande', 'commande', 'WITH');
        $qb->addSelect('commande');

        $qb->andWhere('commandeProduit.produit = :prod')
            ->setParameter('prod', $produit);

        $qb->andWhere('commande.paye = :paye')
            ->setParameter('paye', 0);

        $qb->andWhere('commande.user = :username')
            ->setParameter('username', $user->getUsername());

        return $qb->getQuery()->getResult();
    }
}
