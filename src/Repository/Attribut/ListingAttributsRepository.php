<?php

namespace App\Repository\Attribut;

use App\Entity\Attribut\ListingAttributs;
use App\Entity\Commerce\Commerce;
use App\Entity\InterfaceDef\CommerceInterface;
use App\Entity\Security\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ListingAttributs|null find($id, $lockMode = null, $lockVersion = null)
 * @method ListingAttributs|null findOneBy(array $criteria, array $orderBy = null)
 * @method ListingAttributs[]    findAll()
 * @method ListingAttributs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ListingAttributsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ListingAttributs::class);
    }

    public function search($args)
    {
        $qb = $this->createQueryBuilder('listingAttributs');
        $qb->leftJoin('listingAttributs.attributs', 'attributs', 'WITH');
        $qb->leftJoin('listingAttributs.commerce', 'commerce', 'WITH');
        $qb->addSelect('commerce', 'attributs');

        $motclef = isset($args['motclef']) ? $args['motclef'] : null;
        $commerce = isset($args['commerce']) ? $args['commerce'] : null;

        if ($motclef) {
            $qb->andWhere('listingAttributs.nom LIKE :clef')
                ->setParameter('clef', '%' . $motclef . '%');
        }

        if ($commerce) {
            $qb->andWhere('commerce = :commerce')
                ->setParameter('commerce', $commerce);
        }

        $qb->addOrderBy('listingAttributs.nom');

        $query = $qb->getQuery();

        $results = $query->getResult();

        return $results;
    }

    /**
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getListForForm(CommerceInterface $commerce)
    {
        $qb = $this->createQueryBuilder('listing');

        $qb->andWhere('listing.commerce = :commerce')
            ->setParameter('commerce', $commerce);

        $qb->orderBy('listing.nom');

        return $qb;

    }

    /**
     * @param User $user
     * @return ListingAttributs[]
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

        return $this->search(['commerce' => $commerces]);
    }

}