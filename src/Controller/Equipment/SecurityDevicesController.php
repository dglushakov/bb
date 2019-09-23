<?php


namespace App\Controller\Equipment;


use App\Controller\Equipment\Form\AddAlarmSystemForm;
use App\Controller\Equipment\Form\AddCCTVForm;
use App\Controller\Equipment\Form\AddSafeForm;
use App\Controller\Equipment\Form\EditSecurityDeviceForm;
use App\Controller\Equipment\Form\SecurityDeviceForm;
use App\Entity\AlarmSystem;
use App\Entity\Equipment;
use App\Entity\Facility;
use App\Entity\Safe;
use App\Entity\SecurityDevice;
use App\Repository\AlarmSystemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class SecurityDevicesController extends AbstractController
{
    /**
     * @Route("/safesList", name="safesList")
     */
    public function equipmentList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES');
        $addSafeForm = $this->createForm(AddSafeForm::class, new Safe());
        $addSafeForm->handleRequest($request);
        if ($addSafeForm->isSubmitted() && $addSafeForm->isValid()) {

            $newSafe = $addSafeForm->getData();
            $em->persist($newSafe);
            $em->flush();
        }

        $safesRepo = $this->getDoctrine()->getRepository(Safe::class);
        $safesList = $safesRepo->findBy([], [
            'serial' => 'ASC',
        ]);

        return $this->render('equipment/safeslist.html.twig', [
            'safesList' => $safesList,
            'addSafeForm' => $addSafeForm->createView(),
        ]);
    }


    /**
     * @Route("/safes/edit/{id}", name="editSafe")
     */
    public function safeEdit(Request $request, EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_EDIT');

        $SafesEditRepo = $this->getDoctrine()->getRepository(Safe::class);
        $safeToEdit = $SafesEditRepo->find($id);

        $safeEditForm = $this->createForm(AddSafeForm::class, $safeToEdit);
        $safeEditForm->handleRequest($request);
        if ($safeEditForm->isSubmitted() && $safeEditForm->isValid()) {
            $safeToEdit = $safeEditForm->getData();

            $em->persist($safeToEdit);
            $em->flush();

        }


        return $this->render('equipment/editSafe.html.twig', [
            'editEquipmentForm' => $safeEditForm->createView(),
        ]);
    }


    /**
     * @Route("/safes/delete/{id}", name="deleteSafe")
     */
    public function equipmentDelete(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_DELETE');

        $safesRepo = $this->getDoctrine()->getRepository(Safe::class);
        $safeToDelete = $safesRepo->find($id);

        if ($safeToDelete) {
            $em->remove($safeToDelete);
            $em->flush();
        }

        return $this->redirectToRoute('safesList');
    }


    /**
     * @Route("/alarmList", name="alarmList")
     */
    public function alarmList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES');

        $alarmSystem = new AlarmSystem();
        $alarmSystem->setPkp(0);
        $alarmSystem->setKeyboard(0);
        $alarmSystem->setMotionSensor(0);
        $alarmSystem->setFireSensor(0);
        $alarmSystem->setDoorSensor(0);
        $alarmSystem->setStationaryButton(0);
        $alarmSystem->setWearableButton(0);
        $addAlarmSystemForm = $this->createForm(AddAlarmSystemForm::class, $alarmSystem);
        $addAlarmSystemForm->handleRequest($request);


        if ($addAlarmSystemForm->isSubmitted() && $addAlarmSystemForm->isValid()) {
            $newAlarmSysem = $addAlarmSystemForm->getData();
            $em->persist($newAlarmSysem);
            $em->flush();
        }

        $alarmSystemsRepo = $this->getDoctrine()->getRepository(AlarmSystem::class);
        $alarmSystemsList = $alarmSystemsRepo->findAll();

        return $this->render('equipment/alarmList.html.twig', [
            'alarmSystemsList' => $alarmSystemsList,
            'addAlarmSystemForm' => $addAlarmSystemForm->createView(),
        ]);
    }

    /**
     * @Route("/alarm/edit/{id}", name="editAlarm")
     */
    public function alarmEdit(Request $request, EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_EDIT');

        $alarmSystemsRepo = $this->getDoctrine()->getRepository(AlarmSystem::class);

        /** @var AlarmSystem $systemToEdit */
        $systemToEdit = $alarmSystemsRepo->find($id);


        $systemEditForm = $this->createForm(AddAlarmSystemForm::class, $systemToEdit);
        $systemEditForm->handleRequest($request);
        if ($systemEditForm->isSubmitted() && $systemEditForm->isValid()) {
            $systemToEdit = $systemEditForm->getData();

            $em->persist($systemToEdit);
            $em->flush();

        }


        return $this->render('equipment/editAlarmSystem.html.twig', [
            'editAlarmSystemForm' => $systemEditForm->createView(),
        ]);
    }


    /**
     * @Route("/alarm/delete/{id}", name="deleteAlarm")
     */
    public function alarmDelete(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_DELETE');

        $alarmSystemsRepo = $this->getDoctrine()->getRepository(AlarmSystem::class);
        $alarmTodelete = $alarmSystemsRepo->find($id);

        if ($alarmTodelete) {
            $em->remove($alarmTodelete);
            $em->flush();
        }

        return $this->redirectToRoute('alarmList');
    }

    /**
     * @Route("/securityDevicesList", name="securityDevicesList")
     */
    public function securityDevicesList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES');

        $securityDeviceForm = $this->createForm(SecurityDeviceForm::class, new SecurityDevice());
        $securityDeviceForm->handleRequest($request);

        if ($securityDeviceForm->isSubmitted() && $securityDeviceForm->isValid()) {
            $newDevice = $securityDeviceForm->getData();
            $em->persist($newDevice);
            $em->flush();
            return $this->redirectToRoute('securityDevicesList');
        }

        $devicesRepo = $this->getDoctrine()->getRepository(SecurityDevice::class); //TODO решить что делать с сейфами?
        $notAllocatedDevices = $devicesRepo->getDevicesExceptSafes();

        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facilities = $facilityRepo->findAll();

        $allocatedDevices = [];
        foreach ($facilities as $facility) {
            /** @var Facility $facility */
            $allocatedDevices[$facility->getId()] = [
                'address' => $facility->getAddress(),
                'count' => count($devicesRepo->findBy([
                    'facility' => $facility,
                ]))
            ];
        }


        return $this->render('equipment/securityDevicesList.twig', [
            'devicesList' => $notAllocatedDevices,
            'addDeviceForm' => $securityDeviceForm->createView(),
            'allocatedDevices' => $allocatedDevices,
        ]);

    }

    /**
     * @Route("/securityDevice/edit/{id}", name="securityDevicesEdit")
     */
    public function editSecurityDevic(Request $request, EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES');
        $devicesRepo = $this->getDoctrine()->getRepository(SecurityDevice::class);
        $deviceToEdit = $devicesRepo->find($id);

        $securityDeviceForm = $this->createForm(EditSecurityDeviceForm::class, $deviceToEdit);
        $securityDeviceForm->handleRequest($request);

        if ($securityDeviceForm->isSubmitted() && $securityDeviceForm->isValid()) {
            $newDevice = $securityDeviceForm->getData();
            $em->persist($newDevice);
            $em->flush();
            //return $this->redirectToRoute('securityDevicesList');
        }

        return $this->render('equipment/editSecurityDevice.html.twig', [
            'editEquipmentForm' => $securityDeviceForm->createView(),
        ]);

    }

    /**
     * @Route("/securityDevice/delete/{id}", name="securityDevicesDelit")
     */
    public function deleteSecurityDevice(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_DELETE');

        $devicesRepo = $this->getDoctrine()->getRepository(SecurityDevice::class);
        $deviceToDelete = $devicesRepo->find($id);

        if ($deviceToDelete) {
            $em->remove($deviceToDelete);
            $em->flush();
        }

        return $this->redirectToRoute('securityDevicesList');
    }

    /**
     * @Route("/securityDevice/editFacility/{id}", name="securityDeviceFacilityEdit")
     */
    public function securityDeviceFacilityEdit(Request $request, EntityManagerInterface $em, $id = null)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_EDIT');

        $facilityRepo = $this->getDoctrine()->getRepository(Facility::class);
        $facility = $facilityRepo->find($id);

        $devicesRepo = $this->getDoctrine()->getRepository(SecurityDevice::class);
        $devicesToEdit = $devicesRepo->findBy([
            'facility' => $facility,
        ],
            [
                'serial' => 'ASC',
            ]
        );

        $deviceToAdd = new SecurityDevice();
        $deviceToAdd->setFacility($facility);
        $securityDeviceForm = $this->createForm(SecurityDeviceForm::class, $deviceToAdd);
        $securityDeviceForm->handleRequest($request);

        if ($securityDeviceForm->isSubmitted() && $securityDeviceForm->isValid()) {
            $newDevice = $securityDeviceForm->getData();
            $em->persist($newDevice);
            $em->flush();
            return $this->redirectToRoute('securityDeviceFacilityEdit', ['id' => $facility->getId()]);
        }

        return $this->render('equipment/securityDeviceFacilityEdit.html.twig', [
            'facility' => $facility,
            'devices' => $devicesToEdit,
            'addDeviceForm' => $securityDeviceForm->createView(),
            'equipmentTypes' => Equipment::getEquipmentTypes(),

        ]);
    }

    /**
     * @Route("/securityDevice/unlinkFacility/{id}", name="securityDeviceUnlinkFacility")
     */
    public function securityDeviceUnlinkFacility(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_SECURITY_DEVICES_EDIT');

        $devicesRepo = $this->getDoctrine()->getRepository(SecurityDevice::class);
        $devicesToEdit = $devicesRepo->find($id);
        $facilityIdToRedirect = $devicesToEdit->getFacility()->getid();
        $devicesToEdit->setFacility(null);
        $em->persist($devicesToEdit);
        $em->flush();

        return $this->redirectToRoute('securityDeviceFacilityEdit', ['id' => $facilityIdToRedirect]);
    }

}