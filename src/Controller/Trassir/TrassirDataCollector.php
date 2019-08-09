<?php


namespace App\Controller\Trassir;


use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Dglushakov\Trassir\TrassirServer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrassirDataCollector extends AbstractController
{

    /**
     * @Route("/trassirdatacollect/{id}", name="trassirDataCollect")
     */
    public function trassirDataCollect($id, EntityManagerInterface $em) {
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvr = $trassirNvrRepo->find($id);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);

        $trassirServer = new TrassirServer($trassirNvr->getIp(), getenv('TRASSIR_USER'), getenv('TRASSIR_USER_PASSWORD'), getenv('TRASSIR_SDK_PASSWORD'));

        $trassirNvrData = new TrassirNvrData();
        $trassirNvrData->setHealth($trassirServer->getHealth());
        $trassirNvrData->setObjects($trassirServer->getServerObjects());
        $trassirNvrData->setTrassirNvrId($trassirNvr);

        $em->persist($trassirNvrData);
        $em->flush();

        return true;
    }

    /**
     * @Route("/trassirDataCollctFromAllServers", name="trassirDataCollctFromAllServers")
     */
    public function trassirDataCollctFromAllServers(TrassirDataCollector $trassirDataCollector, EntityManagerInterface $em) {
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findall();


        foreach ($trassirNvrList as $nvrToCollectData) {
            $this->trassirDataCollect($nvrToCollectData->getId(), $em);
        }
        return true;
    }


}