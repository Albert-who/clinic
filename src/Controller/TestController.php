<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController{

    /**
     * @Route("/",name="home_page")
     */
    function indexAction(){
        return $this->render(view:'test/index.html.twig', parameters: ['a'=> 123]);
    }
}