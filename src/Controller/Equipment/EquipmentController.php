<?php


namespace App\Controller\Equipment;


use App\Controller\Equipment\Form\AddEquipmentForm;
use App\Entity\Equipment;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EquipmentController extends AbstractController
{

    /**
     * @Route("/equipmentList", name="equipmentList")
     */
    public function equipmentList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_EQUIPMENT');
        $addEquipmentForm = $this->createForm(AddEquipmentForm::class, new Equipment());
        $addEquipmentForm->handleRequest($request);
        if ($addEquipmentForm->isSubmitted() && $addEquipmentForm->isValid()) {

            $newEquipment = $addEquipmentForm->getData();
            $em->persist($newEquipment);
            $em->flush();
        }

        $equipmentRepo = $this->getDoctrine()->getRepository(Equipment::class);
        $equipmentList = $equipmentRepo->findBy([],[
            'type'=>'ASC',
            'make'=>'ASC',
            'model'=>'ASC',
        ]);

        return $this->render('equipment/equipmentList.html.twig', [
            'equipmentList' => $equipmentList,
            'addEquipmentForm' => $addEquipmentForm->createView(),
            'equipmentTypes' => Equipment::getEquipmentTypes(),
        ]);
    }

    /**
     * @Route("/equipment/edit/{id}", name="editEquipment")
     */
    public function equipmentEdit(Request $request, EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_EQUIPMENT_EDIT');

        $equipmentRepo = $this->getDoctrine()->getRepository(Equipment::class);
        $equipmentToEdit = $equipmentRepo->find($id);

        $editEquipmentForm = $this->createForm(AddEquipmentForm::class, $equipmentToEdit);
        $editEquipmentForm->handleRequest($request);
        if ($editEquipmentForm->isSubmitted() && $editEquipmentForm->isValid()) {
            $equipmentToEdit = $editEquipmentForm->getData();

            $em->persist($equipmentToEdit);
            $em->flush();
            return $this->redirectToRoute('equipmentList');
        }


       return $this->render('equipment/editEquipment.html.twig',[
          'editEquipmentForm' => $editEquipmentForm->createView(),
       ]);
    }

    /**
     * @Route("/equipment/delete/{id}", name="deleteEquipment")
     */
    public function equipmentDelete(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_EQUIPMENT_DELETE');

        $equipmentRepo = $this->getDoctrine()->getRepository(Equipment::class);
        $equipmentToDelete = $equipmentRepo->find($id);

        if($equipmentToDelete) {
            $em->remove($equipmentToDelete);
            $em->flush();
        }

        return $this->redirectToRoute('equipmentList');
    }



    /**
     * @Route("/getEquipmentList/{type}", name="getEquipmentList")
     */
    public function getEquipmentList($type = null)
    {
        $equipmentList=[];
        $equipmentRepo = $this->getDoctrine()->getRepository(Equipment::class);


        if($type && $type!=='all') {
            $equipmentList = $equipmentRepo->findBy(['type'=>$type]);
        } else {
            $equipmentList = $equipmentRepo->findAll();
        }
      return $this->json($equipmentList);
    }

}