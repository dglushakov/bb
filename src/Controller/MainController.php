<?php


namespace App\Controller;


use App\Entity\Facility;
use App\Entity\Safe;
use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(){
        $this->denyAccessUnlessGranted('ROLE_USER');
        $summaryData=[];

        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilities = $facilityRepo->findAll();
        $facilityCount = count($facilities);
        $summaryData['facilityCount'] = $facilityCount;

        $safesRepo = $this->getDoctrine()->getRepository(Safe::class);
        $equippedWithSafes=0;
        foreach ($facilities as $facility) {
            if ($safesRepo->findBy(['facility'=>$facility])){
                $equippedWithSafes++;
            }
        }
        $summaryData['equippedWithSafes']  = $equippedWithSafes;

        $trassirNnrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNnrRepo->findAll();
        $summaryData['trassirNvrCount'] = count($trassirNvrList);
        $equippedWithTrassir=0;
        foreach ($facilities as $facility) {
            if ($trassirNnrRepo->findBy(['facility'=>$facility])){
                $equippedWithTrassir++;
            }
        }
        $summaryData['equippedWithTrassir']  = $equippedWithTrassir;

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirHealth=[];
        foreach ($trassirNvrList as $trassirNvr){
            $trassirHealth[$trassirNvr->getId()] =
                $trassirDataRepo->findOneBy(['trassirNvrId'=>$trassirNvr->getId()],['dateTime'=>'DESC']);
        }

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirNvrOnline=0;
        foreach ($trassirNvrList as $trassirNvr){

            $health = $trassirDataRepo->findOneBy(['trassirNvrId'=>$trassirNvr->getId()],['dateTime'=>'DESC']);
            if ($health and !isset($health->getHealth()['status']) ) {
                $trassirNvrOnline++;
            }
        }
        $summaryData['trassirNvrOnline']  = $trassirNvrOnline;


        return $this->render('home.html.twig', [
            'data'=>$summaryData,
        ]);
    }

}