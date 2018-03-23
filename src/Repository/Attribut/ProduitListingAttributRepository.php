<?php

namespace App\Repository\Attribut;

use App\Entity\Attribut\ProduitListingAttribut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ProduitListingAttribut|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProduitListingAttribut|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProduitListingAttribut[]    findAll()
 * @method ProduitListingAttribut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProduitListingAttributRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, ProduitListingAttribut::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('p')
            ->where('p.something = :value')->setParameter('value', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
