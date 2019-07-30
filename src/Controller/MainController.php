<?php


namespace App\Controller;


use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(){

        return $this->render('home.html.twig', [
            'data'=>'home',
        ]);
    }


    /**
     * @Route("/test", name="test")
     */
    public function test(EntityManagerInterface $em){
        $userRepo = $this->getDoctrine()->getRepository(User::class);
        $users = $userRepo->findAll();


    $data = 'test test test';


        return $this->render('home.html.twig',[
            'users'=>$users,
            'data'=>$data,
            ]);
    }

    /**
     * @Route("/test1", name="test1")
     */

    public function test1(){
        $this->denyAccessUnlessGranted('ROLE_USER');
        $data = 'test1 test1 test1';

        return $this->render('home.html.twig',[
            'data'=>$data,
        ]);
    }
}