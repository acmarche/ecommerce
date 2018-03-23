<?php

namespace App\Repository\Produit;

use App\Entity\Commerce\Commerce;
use App\Entity\Produit\Produit;
use App\Entity\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Produit|null find($id, $lockMode = null, $lockVersion = null)
 * @method Produit|null findOneBy(array $criteria, array $orderBy = null)
 * @method Produit[]    findAll()
 * @method Produit[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Produit::class);
    }

    /**
     * @param $args
     * @return Produit[]
     */
    public function search($args)
    {
        $qb = $this->createQueryBuilder('produit');
        $qb->leftJoin('produit.commerce', 'commerce', 'WITH');
        $qb->leftJoin('produit.categorie', 'categorie', 'WITH');
        $qb->leftJoin('produit.ingredients', 'ingredients', 'WITH');
        $qb->leftJoin('produit.supplements', 'supplements', 'WITH');
        $qb->leftJoin('produit.dimension', 'dimension', 'WITH');
        $qb->addSelect('commerce', 'categorie', 'ingredients', 'supplements', 'dimension');

        $motclef = isset($args['motclef']) ? $args['motclef'] : null;
        $commerce = isset($args['commerce']) ? $args['commerce'] : null;
        $indisponible = isset($args['indisponible']) ? $args['indisponible'] : false;
        $quantite_stock = isset($args['quantite_stock']) ? $args['quantite_stock'] : 1;
        $rand = isset($args['rand']) ? $args['rand'] : false;
        $max = isset($args['max']) ? $args['max'] : false;
        $isFood = isset($args['isFood']) ? $args['isFood'] : null;

        if ($motclef) {
            $qb->andWhere('produit.nom LIKE :clef')
                ->setParameter('clef', '%'.$motclef.'%');
        }

        if ($commerce) {
            $qb->andWhere('produit.commerce = :commerce')
                ->setParameter('commerce', $commerce);
        }

        if (is_bool($isFood)) {
            $qb->andWhere('produit.isFood = :lunch')
                ->setParameter('lunch', $isFood);
        }

        switch ($indisponible) {
            case 1:
                $qb->andWhere('produit.indisponible = :indisponible')
                    ->setParameter('indisponible', 1);
                break;
            case 2:
                //je prends les deux
                break;
            default:
                $qb->andWhere('produit.indisponible = :indisponible')
                    ->setParameter('indisponible', 0);
        }


        if ($max) {
            $qb->setMaxResults($max);
        }


        if ($rand) {
            $qb->addSelect('RAND() as HIDDEN rand')
                ->addOrderBy('rand');
        } else {
            $qb->addOrderBy('produit.nom');
        }

        $query = $qb->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     * @param User $user
     * @return Produit[]
     */
    public function getOwnedByUser(User $user)
    {
        $em = $this->getEntityManager();
        $commerces = $em->getRepository(Commerce::class)->getCommercesOwnedByUser(
            $user
        );

        if (!$commerces) {
            return [];
        }
        $args = [];
        $args['indisponible'] = 2;//tout
        $args['commerce'] = $commerces;

        return $this->search($args);
    }

    /**
     * @param int $max
     * @return Produit[]
     */
    public function getSuggestions($max = 3)
    {
        $args = ['rand' => 1, 'max' => $max];

        return $this->search($args);
    }

    /**
     * @param int $max
     * @return Produit[]
     */
    public function getSuggestionsLunch($max = 3)
    {
        $args = ['rand' => 1, 'max' => $max, 'isFood' => false];

        return $this->search($args);
    }
}
