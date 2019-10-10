<?php

namespace App\Repository;

use App\Entity\TrassirNvr;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TrassirNvr|null find($id, $lockMode = null, $lockVersion = null)
 * @method TrassirNvr|null findOneBy(array $criteria, array $orderBy = null)
 * @method TrassirNvr[]    findAll()
 * @method TrassirNvr[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TrassirNvrRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TrassirNvr::class);
    }

     /**
      * @return TrassirNvr[] Returns an array of TrassirNvr objects
      */

    public function findNvrsToCollectData(){
        $dateTimeToCheck = new \DateTime();
        $dateTimeToCheck->modify('- 30 minutes');

        return $this->createQueryBuilder('t')
            ->andWhere('t.lastHealthAndDataCollectedAt < :dateTime')
            ->orWhere('t.lastHealthAndDataCollectedAt is NULL')
            ->setParameter('dateTime', $dateTimeToCheck)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getNvrArrayByFacilityIdArray($facilityIdArray){

        $facilityString = implode(",", $facilityIdArray);
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT * FROM trassir_nvr WHERE facility_id IN($facilityString) ORDER BY facility_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchAll();

        return $res;
    }
    // /**
    //  * @return TrassirNvr[] Returns an array of TrassirNvr objects
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
    public function findOneBySomeField($value): ?TrassirNvr
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
