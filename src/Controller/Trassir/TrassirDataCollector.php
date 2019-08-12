<?php


namespace App\Controller\Trassir;


use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Dglushakov\Trassir\TrassirServer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Json;

class TrassirDataCollector extends AbstractController
{

    /**
     * @Route("/trassirdatacollect/{id}", name="trassirDataCollect")
     */
    public function trassirDataCollect($id, EntityManagerInterface $em) {
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvr = $trassirNvrRepo->find($id);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);

        $trassirServer = new TrassirServer(
            $trassirNvr->getIp(),
            $_ENV['TRASSIR_USER'],
            $_ENV['TRASSIR_USER_PASSWORD'],
            $_ENV['TRASSIR_SDK_PASSWORD']);
        $trassirNvrData = new TrassirNvrData();
        if($trassirServer->getHealth()) {
            $trassirNvrData->setHealth($trassirServer->getHealth());
        } else {
            $trassirNvrData->setHealth([
                'status'=>'error',
            ]);
        }

        $trassirNvr->setLastHealthAndDataCollectedAt(new \DateTime());
        if($trassirServer->getServerObjects()){
            $trassirNvrData->setObjects($trassirServer->getServerObjects());
        } else {
            $trassirNvrData->setObjects([
                'status'=>'error'
            ]);
        }

        $trassirNvrData->setTrassirNvrId($trassirNvr);
        $trassirNvrData->setDateTime(new \DateTime());

        dump($trassirServer);
        dump($trassirNvr);
        dd($trassirNvrData);
        $em->persist($trassirNvrData);
        $em->flush();

        return new Response('true');
    }

    /**
     * @Route("/trassirDataCollectNew", name="trassirDataCollectNew")
     */
    public function trassirDataCollectNew(EntityManagerInterface $em) {
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findNvrsToCollectData();

        //$trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);

        foreach ($trassirNvrList as $nvrToCollectData) {
            $trassirServer = new TrassirServer($nvrToCollectData->getIp(),
                getenv('TRASSIR_USER'),
                getenv('TRASSIR_USER_PASSWORD'),
                getenv('TRASSIR_SDK_PASSWORD'));
            $trassirNvrData = new TrassirNvrData();
            if($trassirServer->getHealth()) {
                $trassirNvrData->setHealth($trassirServer->getHealth());
                $trassirNvrData->setSuccess(true);
            } else {
                $trassirNvrData->setHealth([
                    'status'=>'error',
                ]);
                $trassirNvrData->setSuccess(false);
            }

            if($trassirServer->getServerObjects()){
                $trassirNvrData->setObjects($trassirServer->getServerObjects());
            } else {
                $trassirNvrData->setObjects([
                    'status'=>'error'
                ]);
            }

            $trassirNvrData->setTrassirNvrId($nvrToCollectData);
            $trassirNvrData->setDateTime(new \DateTime());
            $em->persist($trassirNvrData);

            $nvrToCollectData->setLastHealthAndDataCollectedAt(new \DateTime());
            $nvrToCollectData->setName($trassirServer->getName());
            $nvrToCollectData->setGuid($trassirServer->getGuid());
            $em->persist($nvrToCollectData);

        }
        $em->flush();


        return new JsonResponse([
            'result'=>true,
        ]);
    }


}