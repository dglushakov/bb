<?php

namespace App\Repository;

use App\Entity\SecurityDevice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method SecurityDevice|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityDevice|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityDevice[]    findAll()
 * @method SecurityDevice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityDeviceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, SecurityDevice::class);
    }

    // /**
    //  * @return SecurityDevice[] Returns an array of SecurityDevice objects
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
    public function findOneBySomeField($value): ?SecurityDevice
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
