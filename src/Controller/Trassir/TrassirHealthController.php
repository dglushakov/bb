<?php


namespace App\Controller\Trassir;


use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TrassirHealthController extends AbstractController
{

    /**
     * @Route("/trassirHealthList", name="trassirHealthList")
     */
    public function trassirHealthList(){
        $this->denyAccessUnlessGranted('ROLE_NVR_HEALTH_LIST');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findBy([],['name'=>'ASC', 'Ip'=>'ASC']);

        $trassirNvrIdList =[];
        foreach ($trassirNvrList as $trassirNvr){
            $trassirNvrIdList[] = $trassirNvr->getId();
        }

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);

//        $trassirHealth=[];
//        foreach ($trassirNvrIdList as $id){
//            $trassirHealth[$id] = $trassirDataRepo->findOneBy(['trassirNvrId' => $id],['dateTime'=>'DESC']);
//        }

        $trassirHealth= $trassirDataRepo->getLastDataForEachNvrInList($trassirNvrIdList);

        return $this->render('trassir/trassirHealthList.html.twig', [
            'servers' => $trassirNvrList,
            'trassirHealth'=>$trassirHealth,
        ]);
    }

    /**
     * @Route("/trassirHealthSingleNvr/{id}", name="trassirHealthSingleNvr")
     */
    public function trassirHealthSingleNvr($id){
        $this->denyAccessUnlessGranted('ROLE_NVR_HEALTH_LIST');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvr=$trassirNvrRepo->findOneBy(['id'=>$id]);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirHealthArray = $trassirDataRepo->findBy(['trassirNvrId'=>$id],['dateTime'=>'DESC'], 1000,0);


        $trassirHealth=[];
        $previousHealthData = null;
        $counter =0;
        foreach ($trassirHealthArray as $healthData){
            $counter++;
            $newHealthData = $healthData->getHealth();
            if($this->isHealthDataChanges($newHealthData, $previousHealthData) || ($counter === count($trassirHealthArray))) {
                $trassirHealth[$healthData->getDateTime()->format('Y-m-d H:i:s')]=$newHealthData;
            }
            $previousHealthData = $newHealthData;
        }

        return $this->render('trassir/trassirHealthSingleServer.html.twig',[
            'server' => $trassirNvr,
            'trassirHealth'=>$trassirHealth,
        ]);
    }


    private function isHealthDataChanges($newData, $previousData) {
        $result = true;
        if($previousData===null) {
            return $result;
        }

        if(
            $newData['channels_online'] === $previousData['channels_online']
            && $newData['channels_total'] === $previousData['channels_total']
            && $newData['disks'] === $previousData['disks']
            && $newData['network'] === $previousData['network']
            && $newData['database'] === $previousData['database']
            && $newData['automation'] === $previousData['automation']

        ) {
            $result=false;
        }

        return $result;
    }
}