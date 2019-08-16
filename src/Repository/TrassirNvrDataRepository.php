<?php

namespace App\Repository;

use App\Entity\TrassirNvrData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrassirNvrData|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrassirNvrData|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrassirNvrData[]    findAll()
 * @method TrassirNvrData[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrassirNvrDataRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrassirNvrData::class);
    }

    public function getFreshData(){
        return $this->createQueryBuilder('t')
            ->andWhere('t.dateTime > :dateTime')
            ->setParameter('dateTime', new \DateTime('-7 days'))
            ->orderBy('t.trassirNvrId', 'ASC')
            ->orderBy('t.dateTime', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
            ;
    }

    // /**
    //  * @return TrassirNvrData[] Returns an array of TrassirNvrData objects
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
    public function findOneBySomeField($value): ?TrassirNvrData
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
