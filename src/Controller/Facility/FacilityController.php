<?php


namespace App\Controller\Facility;


use App\Controller\Facility\Forms\AddFacilityForm;
use App\Controller\Facility\Forms\EditFacilityForm;
use App\Controller\Facility\Forms\testForm;
use App\Entity\AlarmSystem;
use App\Entity\Facility;
use App\Entity\Safe;
use App\Entity\SecurityDevice;
use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FacilityController extends AbstractController
{
    /**
     * @Route("/facilitylist", name="facilitylist")
     */
    public function facilityList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_FACILITY_LIST');
        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilityList = $facilityRepo->findBy([],
            [
                'country' => 'ASC',
                'city' => 'ASC',
                'street' => 'ASC',
                'house' => 'ASC',
            ]);

        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $safesRepo = $this->getDoctrine()->getRepository(Safe::class);
        $alarmSystems = $this->getDoctrine()->getRepository(AlarmSystem::class);

        $nvrCount = [];
        foreach ($facilityList as $facility) {
            $nvrCount[$facility->getId()] = count($trassirNvrRepo->findBy(['facility' => $facility]));
            $nvrCount[$facility->getId()] += count($safesRepo->findBy(['facility' => $facility]));
            $nvrCount[$facility->getId()] += count($alarmSystems->findBy(['facility' => $facility]));
        }

        $addFacilityForm = $this->createForm(AddFacilityForm::class, new Facility());
        $addFacilityForm->handleRequest($request);
        if ($addFacilityForm->isSubmitted()) {
            /** @var $newFacility Facility */
            $newFacility = $addFacilityForm->getData();
            $formData = $request->request->get('add_facility_form');

            $isDuplicated = $facilityRepo->findBy([
                'country' => $formData['Country'],
                'city' => $formData['City'],
                'street' => $formData['Street'],
                'house' => $formData['House'],
            ]);

            if (!$isDuplicated) {
                if ($newFacility->getCity() === null) {
                    $newFacility->setCity(trim($formData['City']));
                    $newFacility->setRegion(trim($formData['Region']));
                };

                if (!$newFacility->getName()) {
                    $newFacility->setName($newFacility->getCity() . ' ' . $newFacility->getStreet());
                }

                $em->persist($newFacility);
                $em->flush();
                $this->refreshCoordinates($newFacility->getId(), $em);
                return $this->redirectToRoute('facilitylist');
            }

        }


        return $this->render('facility/facilityList.html.twig', [
            'facilities' => $facilityList,
            'form' => $addFacilityForm->createView(),
            'nvrCount' => $nvrCount,
        ]);
    }

    /**
     * @Route ("/facility/edit/{id}", name="editFacility")
     */
    public function editFacility(EntityManagerInterface $em, Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_FACILITY_EDIT');
        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilityToEdit = $facilityRepo->find($id);


        $editFacilityForm = $this->createForm(EditFacilityForm::class, $facilityToEdit);
        $editFacilityForm->handleRequest($request);
        if ($editFacilityForm->isSubmitted() && $editFacilityForm->isValid()) {

            /** @var Facility $data */
            $data = $editFacilityForm->getData();

            $facilityToEdit->setName($data->getName());
            $facilityToEdit->setPostCode($data->getPostCode());
            $facilityToEdit->setStreetType($data->getStreetType());
            $facilityToEdit->setStreet($data->getStreet());
            $facilityToEdit->setHouse($data->getHouse());
            $facilityToEdit->setBuildingType($data->getBuildingType());
            $facilityToEdit->setBuilding($data->getBuilding());
            $facilityToEdit->setRoom($data->getRoom());

            $facilityToEdit->setLat($data->getLat());
            $facilityToEdit->setLon($data->getLon());


            $em->persist($facilityToEdit);
            $em->flush();
            //return $this->redirectToRoute('facilitylist');
        }

        return $this->render('facility/editFacility.html.twig', [
            'editFacilityForm' => $editFacilityForm->createView(),
            'facility' => $facilityToEdit,
        ]);
    }

    /**
     * @Route ("/facility/delete/{id}", name="deleteFacility")
     */
    public function deleteFacility(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_FACILITY_DELETE');
        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilityToDelete = $facilityRepo->find($id);


        if ($facilityToDelete) {
            $em->remove($facilityToDelete);
            $em->flush();
        }

        return $this->redirectToRoute('facilitylist');
    }

    /**
     * @Route("/getcities/{country}", name="getCities")
     */
    public function getCities($country = null)
    {
        if ($country == 'RU') {
            $cities = Facility::getRussianCities();
        } else if ($country == 'BY') {
            $cities = Facility::getByCities();
        }
        return new JsonResponse($cities);
    }

    /**
     * @Route("/facilityPassport/{id}", name = "facilityPassport")
     */
    public function facilityPassport($id)
    {
        $this->denyAccessUnlessGranted('ROLE_FACILITY');
        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilityToShow = $facilityRepo->find($id);

        $securityDeviceRepo = $this->getDoctrine()->getRepository(SecurityDevice::class);
        $safeRepo = $this->getDoctrine()->getRepository(Safe::class);
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);

        $trassirNvrList = $trassirNvrRepo->findBy(['facility' => $facilityToShow]);

        $trassirDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirHealth = [];
        foreach ($trassirNvrList as $trassirNvr) {
            $trassirHealth[$trassirNvr->getId()] = $trassirDataRepo->findOneBy(['trassirNvrId' => $trassirNvr->getId()], ['dateTime' => 'DESC']);
        }

        return $this->render('facility/facilityPassport.html.twig', [
            'facility' => $facilityToShow,
            'securityDevices' => $securityDeviceRepo->findBy(['facility' => $facilityToShow]),
            'safes' => $safeRepo->findBy(['facility' => $facilityToShow]),
            'trassirNvr' => $trassirNvrList,
            'trassirNvrHealth' => $trassirHealth,
        ]);
    }

    /**
     * @Route("/refreshCoordinates/{id}" , name ="refreshCoordinates")
     */
    public function refreshCoordinates($id, EntityManagerInterface $entityManager)
    {
        $this->denyAccessUnlessGranted('ROLE_FACILITY');

        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        /**@var $facility Facility */
        $facility = $facilityRepo->find($id);


        $url = 'https://nominatim.openstreetmap.org/search?';
        $params =
            [
                'city'=>$facility->getCity(),
                'street'=>$facility->getStreet(),
                'housenumber'=>$facility->getHouse(),
                'format'=>'json',
                'limit'=>1
            ];
        $ch = curl_init($url.http_build_query($params));
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
        $output = curl_exec($ch);
        curl_close($ch);

        $output = json_decode($output);

        if (!empty($output[0])) {
            $facility->setLat($output[0]->lat);
            $facility->setLon($output[0]->lon);
        } else {
            $facility->setLat("error");
            $facility->setLon("error");
        }


        $entityManager->persist($facility);
        $entityManager->flush();

        //$geoTargetData = json_decode($result, true); //переводим JSON в массив
        return new JsonResponse($output);
    }

}