<?php

namespace Symfonier\ClientBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DefaultController extends Controller
{
    public function indexAction()
    {
    	
        return $this->render('SymfonierClientBundle:Default:index.html.twig', array('name' => "This is the main page (not app!)"));
    }

    public function systemInfoAction()
    {
    	phpinfo();

    }

}
