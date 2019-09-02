<?php


namespace App\Controller\Facility;


use App\Controller\Facility\Forms\AddFacilityForm;
use App\Controller\Facility\Forms\EditFacilityForm;
use App\Entity\Facility;
use App\Entity\TrassirNvr;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FacilityController extends AbstractController
{

    /**
     * @Route("/facilitylist", name="facilitylist")
     */
    public function trassirNvrList(Request $request, EntityManagerInterface $em)
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
        foreach ($facilityList as $facility) {

            $nvrCount[$facility->getId()] = count($trassirNvrRepo->findBy(['facility'=>$facility]));
        }


        $addFacilityForm = $this->createForm(AddFacilityForm::class, new Facility());
        $addFacilityForm->handleRequest($request);
        if ($addFacilityForm->isSubmitted() && $addFacilityForm->isValid()) {

            /** @var $newFacility Facility */
            $newFacility = $addFacilityForm->getData();
            if(!$newFacility->getName()){
                $newFacility->setName($newFacility->getCity().' '.$newFacility->getStreet());
            }

            $em->persist($newFacility);
            $em->flush();
            return $this->redirectToRoute('facilitylist');
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
            $facilityToEdit = $editFacilityForm->getData();
            $em->persist($facilityToEdit);
            $em->flush();
            return $this->redirectToRoute('facilitylist');
        }

        return $this->render('facility/editFacility.html.twig', [
            'editFacilityForm' => $editFacilityForm->createView(),
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



}