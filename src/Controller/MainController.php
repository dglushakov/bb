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
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('home.html.twig', [
            'data'=>'home',
        ]);
    }

}