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

class TrassirDataCollector extends AbstractController
{

    /**
     * @Route("/trassirdatacollect/{id}", name="trassirDataCollect")
     * @param $id
     * @param EntityManagerInterface $em
     * @return Response
     * @throws \Exception
     */
    public function trassirDataCollect($id, EntityManagerInterface $em) {
        $result = true;
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvr = $trassirNvrRepo->find($id);


        $trassirServer = new TrassirServer($trassirNvr->getIp(),
            $_ENV['TRASSIR_USER'],
            $_ENV['TRASSIR_USER_PASSWORD'],
            $_ENV['TRASSIR_SDK_PASSWORD']);
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
            $result= false;
            $trassirNvrData->setObjects([
                'status'=>'error'
            ]);
        }

        $trassirNvrData->setTrassirNvrId($trassirNvr);
        $trassirNvrData->setDateTime(new \DateTime());
        $em->persist($trassirNvrData);

        $trassirNvr->setLastHealthAndDataCollectedAt(new \DateTime());
        $trassirNvr->setName($trassirServer->getName());
        $trassirNvr->setGuid($trassirServer->getGuid());
        $em->persist($trassirNvr);

        $em->flush();
        $converted_res = ($result) ? 'true' : 'false';
        return new Response($converted_res);
    }

    /**
     * @Route("/trassirDataCollectGroup", name="trassirDataCollectGroup")
     * @throws \Exception
     */
    public function trassirDataCollectGroup(EntityManagerInterface $em) {
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findNvrsToCollectData();

        foreach ($trassirNvrList as $nvrToCollectData) {
            $this->trassirDataCollect($nvrToCollectData->getId(), $em);
        }
        return new JsonResponse([
            'result'=>true,
        ]);
    }


}