<?php


namespace App\Controller\Equipment;


use App\Controller\Equipment\Form\AddAlarmSystemForm;
use App\Controller\Equipment\Form\AddSafeForm;
use App\Entity\AlarmSystem;
use App\Entity\Safe;
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
        $safesList = $safesRepo->findBy([],[
            'serial'=>'ASC',
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


        return $this->render('equipment/editSafe.html.twig',[
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

        if($safeToDelete) {
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


        return $this->render('equipment/editAlarmSystem.html.twig',[
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

        if($alarmTodelete) {
            $em->remove($alarmTodelete);
            $em->flush();
        }

        return $this->redirectToRoute('alarmList');
    }
}