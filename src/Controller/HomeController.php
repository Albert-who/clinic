<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController{

    /**
     * @Route("/",name="home_page")
     */
    function indexAction(){
        return $this->render(view:'home/index.html.twig');
    }
}