<?php

namespace App\Repository;

use App\Entity\TrassirServerData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrassirServerData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrassirServerData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrassirServerData[]    findAll()
 * @method TrassirServerData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrassirServerDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrassirServerData::class);
    }

    // /**
    //  * @return TrassirServerData[] Returns an array of TrassirServerData objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TrassirServerData
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
