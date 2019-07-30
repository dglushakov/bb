<?php


namespace App\Controller;


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
    public function test(){
    $data = 'test test test';

        return $this->render('home.html.twig',[
            'data'=>$data,
            ]);
    }

    /**
     * @Route("/test1", name="test1")
     */

    public function test1(){
        $data = 'test1 test1 test1';

        return $this->render('home.html.twig',[
            'data'=>$data,
        ]);
    }
}