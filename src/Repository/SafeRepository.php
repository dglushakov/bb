<?php

namespace App\Repository;

use App\Entity\Safe;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Safe|null find($id, $lockMode = null, $lockVersion = null)
 * @method Safe|null findOneBy(array $criteria, array $orderBy = null)
 * @method Safe[]    findAll()
 * @method Safe[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SafeRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Safe::class);
    }

    // /**
    //  * @return Safe[] Returns an array of Safe objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Safe
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
