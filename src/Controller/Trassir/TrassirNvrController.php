<?php


namespace App\Controller\Trassir;


use App\Controller\Trassir\Forms\AddNvrForm;
use App\Controller\Trassir\Forms\EditTrassirNvrForm;
use App\Entity\TrassirNvr;
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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //$username = getenv('TRASSIR_USER');
        //$pass = getenv('TRASSIR_USER_PASSWORD');
        $sdkPass = getenv('TRASSIR_SDK_PASSWORD');
        //$s = new  TrassirServer('10.18.36.33', $username, $pass, $sdkPass);
        // $obj = $s->getServerObjects();
        //dd($obj);

        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrList = $trassirNvrRepo->findAll();

        $addNvrForm = $this->createForm(AddNvrForm::class, new TrassirNvr('127.0.0.1'));

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
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        //$this->denyAccessUnlessGranted('ROLE_USERS_EDIT');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrToEdit = $trassirNvrRepo->find($id);


        $EditTrassirNvrForm = $this->createForm(EditTrassirNvrForm::class, $trassirNvrToEdit);
        $EditTrassirNvrForm->handleRequest($request);
        if ($EditTrassirNvrForm->isSubmitted() && $EditTrassirNvrForm->isValid()) {
            $trassirNvrToEdit = $EditTrassirNvrForm->getData();
            $em->persist($trassirNvrToEdit);
            $em->flush();
            return $this->redirectToRoute('nvrList');
        }

        return $this->render('trassir/editTrassirNvr.html.twig', [
            'editTrassirNvrForm' => $EditTrassirNvrForm->createView(),
        ]);
    }


    /**
     * @Route ("/trassirNvr/delete/{id}", name="deleteTrassirNvr")
     */
    public function deleteTrassirNvr(EntityManagerInterface $em, $id)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $trassirNvrRepo = $this->getDoctrine()->getRepository(TrassirNvr::class);
        $trassirNvrToDelete = $trassirNvrRepo->find($id);
        if ($trassirNvrToDelete) {
            $em->remove($trassirNvrToDelete);
            $em->flush();
        }
        return $this->redirectToRoute('nvrList');
    }

}