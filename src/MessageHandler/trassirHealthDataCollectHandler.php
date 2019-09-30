<?php


namespace App\MessageHandler;


use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use App\Message\trassirHealthDataCollect;
use Dglushakov\Trassir\TrassirServer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class trassirHealthDataCollectHandler implements MessageHandlerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    public function __invoke(trassirHealthDataCollect $dataCollectMesasge)
    {
        //$id = $dataCollectMesasge->getId();

        $trassirNvrRepo = $this->entityManager->getRepository(TrassirNvr::class);
        $trassirNvr = $trassirNvrRepo->find($dataCollectMesasge->getId());


        $trassirServer = new TrassirServer($trassirNvr->getIp(),
            $_ENV['TRASSIR_USER'],
            $_ENV['TRASSIR_USER_PASSWORD'],
            $_ENV['TRASSIR_SDK_PASSWORD']);
        $trassirNvrData = new TrassirNvrData();
        if($health = $trassirServer->getHealth()) {
            $trassirNvrData->setHealth($health);
            $trassirNvrData->setSuccess(true);
        } else {
            $trassirNvrData->setHealth([
                'status'=>'error',
            ]);
            $trassirNvrData->setSuccess(false);
        }

        if($objects = $trassirServer->getServerObjects()){
            $trassirNvrData->setObjects($objects);
            $trassirNvr->setName($trassirServer->getName());
            $trassirNvr->setGuid($trassirServer->getGuid());
        } else {
            $trassirNvrData->setObjects([
                'status'=>'error'
            ]);
        }

        $trassirNvrData->setTrassirNvrId($trassirNvr);
        $trassirNvrData->setDateTime(new \DateTime());
        $this->entityManager->persist($trassirNvrData);

        $trassirNvr->setLastHealthAndDataCollectedAt(new \DateTime());

        $this->entityManager->persist($trassirNvr);

        $this->entityManager->flush();
    }


}