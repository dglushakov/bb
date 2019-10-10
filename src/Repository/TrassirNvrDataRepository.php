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


    public function getLastDataForEachNvr(){

        $conn = $this->getEntityManager()->getConnection();
        $sql = 'SELECT nvr_data2.nvr, nvr_data2.time, nvr_data1.success
                FROM trassir_nvr_data as nvr_data1
	            JOIN (SELECT MAX(date_time) as time, trassir_nvr_id_id as nvr FROM trassir_nvr_data GROUP BY trassir_nvr_id_id) as nvr_data2
                ON nvr_data1.trassir_nvr_id_id = nvr_data2.nvr AND nvr_data1.date_time=nvr_data2.time';
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchAll();
        return $res;
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
