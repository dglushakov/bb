<?php


namespace App\MessageHandler;


use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use App\Message\trassirHealthDataCollect;
//use Dglushakov\Trassir\TrassirServer;
use dglushakov\Trassir\TrassirNvr\TrassirNVR as TrassirServer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class trassirHealthDataCollectHandler implements MessageHandlerInterface
{

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

    }

    public function __invoke(trassirHealthDataCollect $dataCollectMessage)
    {

        $trassirNvrRepo = $this->entityManager->getRepository(TrassirNvr::class);
        /** @var  $trassirNvr TrassirNvr */
        $trassirNvr = $trassirNvrRepo->find($dataCollectMessage->getNvrId());


        $trassirServer = new TrassirServer($trassirNvr->getIp(),
            $_ENV['TRASSIR_USER'],
            $_ENV['TRASSIR_USER_PASSWORD'],
            $_ENV['TRASSIR_SDK_PASSWORD']);
        $trassirNvrData = new TrassirNvrData();
        if($health = $trassirServer->getNvrHealth()) {
            $trassirNvrData->setHealth($health);
            $trassirNvrData->setSuccess(true);
        } else {
            $trassirNvrData->setHealth([
                'status'=>'error',
            ]);
            $trassirNvrData->setSuccess(false);
        }

        if ($objects = $trassirServer->getObjectsTree()) {
            foreach ($objects as $object) {
                if($object['class']=='Server') {
                    $trassirNvr->setName($object['name']);
                    $trassirNvr->setGuid($object['guid']);
                }
            }

            if ($userNames = $trassirServer->getUserNames()) {
                $trassirNvrData->setSuccess(true);
                $objects['UserNames'] =$userNames;
            }
            $trassirNvrData->setObjects($objects);

        } else {
            $result = false;
            $trassirNvrData->setObjects([
                'status' => 'error'
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