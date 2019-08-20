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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findBy([],['name'=>'ASC', 'Ip'=>'ASC']);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
//        $trassirData = $trassirDataRepo->getFreshData();

        $trassirHealth=[];
        foreach ($trassirNvrList as $trassirNvr){
            $trassirHealth[$trassirNvr->getId()] = $trassirDataRepo->findOneBy(['trassirNvrId'=>$trassirNvr->getId()],['dateTime'=>'DESC']);
        }


        return $this->render('trassir/trassirHealthList.html.twig', [
            'servers' => $trassirNvrList,
            'trassirHealth'=>$trassirHealth,
        ]);
    }

    /**
     * @Route("/trassirHealthSingleNvr/{id}", name="trassirHealthSingleNvr")
     */
    public function trassirHealthSingleNvr($id){
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvr=$trassirNvrRepo->findOneBy(['id'=>$id]);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirHealthArray = $trassirDataRepo->findBy(['trassirNvrId'=>$id],['dateTime'=>'DESC'], 250,0);


        //dd($trassirHealthArray);
        $trassirHealth=[];
        foreach ($trassirHealthArray as $healthData){
            $trassirHealth[$healthData->getDateTime()->format('Y-m-d H:i:s')]=$healthData->getHealth();
        }


        //dd($trassirHealth);
        return $this->render('trassir/trassirHealthSingleServer.html.twig',[
            'server' => $trassirNvr,
            'trassirHealth'=>$trassirHealth,
        ]);
    }

}