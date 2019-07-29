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

        return $this->render('home.html.twig');
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
}