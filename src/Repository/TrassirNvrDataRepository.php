<?php

namespace App\Repository;

use App\Entity\TrassirNvrData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use PDO;
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

    public function getLastDataForEachNvrInList($idList){
        $idList = implode(",", $idList);
        $conn = $this->getEntityManager()->getConnection();
        $sql = "SELECT nvr_data2.nvr, nvr_data2.time, nvr_data1.success, nvr_data1.health
                FROM trassir_nvr_data as nvr_data1
	            JOIN (SELECT MAX(date_time) as time, trassir_nvr_id_id as nvr FROM trassir_nvr_data WHERE trassir_nvr_id_id IN ($idList) GROUP BY trassir_nvr_id_id) as nvr_data2
                ON nvr_data1.trassir_nvr_id_id = nvr_data2.nvr AND nvr_data1.date_time=nvr_data2.time";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $res =$stmt->fetchAll();

        $resultArray=[];
        foreach ($res as $row ){
            $resultArray[$row['nvr']] = json_decode($row['health'], true);
        }

        return $resultArray;

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

}
