<?php


namespace App\Controller\Trassir;


use App\Controller\Trassir\Forms\AddNvrForm;
use App\Controller\Trassir\Forms\EditTrassirNvrForm;
use App\Entity\TrassirNvr;
use App\Entity\TrassirNvrData;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Dglushakov\Trassir\TrassirServer;

class TrassirNvrController extends AbstractController
{

    /**
     * @Route("/nvrlist", name="nvrList")
     */
    public function trassirNvrList(Request $request, EntityManagerInterface $em)
    {
        $this->denyAccessUnlessGranted('ROLE_NVR_LIST');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findBy([],['name'=>'ASC', 'Ip'=>'ASC']);

        $addNvrForm = $this->createForm(AddNvrForm::class, new TrassirNvr());

        $addNvrForm->handleRequest($request);
        if ($addNvrForm->isSubmitted() && $addNvrForm->isValid()) {
            /**
             * @var $newNvr TrassirNvr
             */
            $newNvr = $addNvrForm->getData();


            $em->persist($newNvr);
            $em->flush();
            return $this->redirectToRoute('nvrList');
        }


        return $this->render('trassir/trassirNvrList.html.twig', [
            'servers' => $trassirNvrList,
            'addNvrForm' => $addNvrForm->createView(),
        ]);
    }

    /**
     * @Route ("/trassirNvr/edit/{id}", name="editTrassirNvr")
     */
    public function editTrassirNvr(EntityManagerInterface $em, Request $request, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_NVR_EDIT');
        //$this->denyAccessUnlessGranted('ROLE_USERS_EDIT');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrToEdit = $trassirNvrRepo->find($id);


        $EditTrassirNvrForm = $this->createForm(EditTrassirNvrForm::class, $trassirNvrToEdit);
        $EditTrassirNvrForm->handleRequest($request);
        if ($EditTrassirNvrForm->isSubmitted() && $EditTrassirNvrForm->isValid()) {

            /** @var TrassirNvr $trassirNvrToEdit */
            $trassirNvrToEdit = $EditTrassirNvrForm->getData();

            /** @var TrassirNvr $formData */
            $formData = $request->request->get('edit_trassir_nvr_form');
            if($formData['Facility']=='') {
                $trassirNvrToEdit->setFacility(null);
            }


            //dd($trassirNvrToEdit);
            $em->persist($trassirNvrToEdit);
            $em->flush();
            return $this->redirectToRoute('nvrList');
        }

        return $this->render('trassir/editTrassirNvr.html.twig', [
            'editTrassirNvrForm' => $EditTrassirNvrForm->createView(),
            'server'=>$trassirNvrToEdit,
        ]);
    }


    /**
     * @Route ("/trassirNvr/delete/{id}", name="deleteTrassirNvr")
     */
    public function deleteTrassirNvr(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_NVR_DELETE');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrToDelete = $trassirNvrRepo->find($id);

        $trassirNvrDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);
        $trassirNvrDataListToDelete = $trassirNvrDataRepo->findBy(['trassirNvrId'=>$trassirNvrToDelete]);

        if ($trassirNvrToDelete) {
            foreach ($trassirNvrDataListToDelete as $dataToTdelete){
                $em->remove($dataToTdelete);
            }

            $em->remove($trassirNvrToDelete);
            $em->flush();
        }
        return $this->redirectToRoute('nvrList');
    }


    /**
     * @Route("/trassirUsersList", name="trassirUsersList")
     */
    public function trassirusersList()
    {

        $this->denyAccessUnlessGranted('ROLE_USER');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findBy([],['name'=>'ASC', 'Ip'=>'ASC']);

        $trassirNVrDataRepo = $this->getDoctrine()->getRepository(TrassirNvrData::class);

        /**
         * @var $trassirNvrDataList TrassirNvrData[]
         */
        $trassirNvrDataList = [];
        foreach ($trassirNvrList as $trassirNvr) {
            $trassirNvrDataList[] = $trassirNVrDataRepo->findOneBy([
                'trassirNvrId' => $trassirNvr,
                'success' => true,

            ], [
                'dateTime' => 'DESC'
            ]);
        }


        $trassirUsersData=[];
        foreach ($trassirNvrDataList as $trassirNvrData){
            if($trassirNvrData !== NULL){
                foreach ($trassirNvrData->getObjects()['UserNames'] as $username){
                    $trassirUsersData[$username]['ip'][$trassirNvrData->getTrassirNvrId()->getName()]=$trassirNvrData->getTrassirNvrId()->getIp();
                }
            }
        }
        ksort($trassirUsersData);

        return $this->render('trassir/trassirusersList.html.twig', [
            //'trassirNvrDataList' => $trassirNvrDataList,
            'nvrList'=>$trassirNvrList,
            'trassirUsers'=>$trassirUsersData,

        ]);
    }

}