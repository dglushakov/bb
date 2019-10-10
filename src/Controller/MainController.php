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
    public function home()
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $summaryData = [];

        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilityIdArray = $facilityRepo->getFacilityIdList();

        $facilityCount = count($facilityIdArray);
        $summaryData['facilityCount'] = $facilityCount;

        $safesRepo = $this->getDoctrine()->getRepository(Safe::class);
        $safesArray = $safesRepo->getSafesArrayByFacilityIdArray($facilityIdArray);

        $equippedWithSafes = 0;
        $equippedWithSafesViolations = 0;

        $previousFacility=0;
        foreach ($safesArray as $safe){
            if ($safe['facility_id']!=$previousFacility) {
                if ($safe['status'] == "Violations") {
                    $equippedWithSafesViolations++;
                }
                if ($safe['status'] == "OK") {
                    $equippedWithSafes++;
                }
            } else {
                if ($safe['status'] == "Violations") {
                    $equippedWithSafes--;
                    $equippedWithSafesViolations ++;
                }
            }
            $previousFacility = $safe['facility_id'];
        }
        $summaryData['equippedWithSafes'] = $equippedWithSafes;
        $summaryData['equippedWithSafesViolations'] = $equippedWithSafesViolations;

        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $summaryData['trassirNvrCount'] = $trassirNvrRepo->count([]);

        $trassirNvrArray = $trassirNvrRepo->getNvrArrayByFacilityIdArray($facilityIdArray);

        $equippedWithTrassir = 0;

        $previousFacility=0;
        foreach ($trassirNvrArray as $trassirNvr) {
            if ($previousFacility != $trassirNvr['facility_id']) {
                $equippedWithTrassir++;
            }
            $previousFacility= $trassirNvr['facility_id'];
        }
        $summaryData['equippedWithTrassir'] = $equippedWithTrassir;


        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirData = $trassirDataRepo->getLastDataForEachNvr();
        $trassirNvrOnline = 0;

        foreach ($trassirData as $data) {
            if ($data['success']==1){
                $trassirNvrOnline++;
            }
        }

        $summaryData['trassirNvrOnline'] = $trassirNvrOnline;
//        foreach ($trassirNvrList as $trassirNvr) {
//
//            $health = $trassirDataRepo->findOneBy(['trassirNvrId' => $trassirNvr->getId()], ['dateTime' => 'DESC']);
//            if ($health and !isset($health->getHealth()['status'])) {
//                $trassirNvrOnline++;
//            }
//        }



        return $this->render('home.html.twig', [
            'data' => $summaryData,
        ]);
    }

}