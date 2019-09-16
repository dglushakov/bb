<?php


namespace App\Controller\Equipment;


use App\Controller\Equipment\Form\AddSafeForm;
use App\Entity\Safe;
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
}