<?php

namespace App\Repository;

use App\Entity\Facility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Facility|null find($id, $lockMode = null, $lockVersion = null)
 * @method Facility|null findOneBy(array $criteria, array $orderBy = null)
 * @method Facility[]    findAll()
 * @method Facility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FacilityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Facility::class);
    }

    public function getFacilityIdList()
    {
        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT id FROM facility';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchAll(\PDO::FETCH_COLUMN, 0);

        return $res;
    }

    // /**
    //  * @return Facility[] Returns an array of Facility objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Facility
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
