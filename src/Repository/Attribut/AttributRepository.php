<?php

namespace App\Repository\Attribut;

use App\Entity\Attribut\Attribut;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Attribut|null find($id, $lockMode = null, $lockVersion = null)
 * @method Attribut|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attribut[]    findAll()
 * @method Attribut[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttributRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Attribut::class);
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
