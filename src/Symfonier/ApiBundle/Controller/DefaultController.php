<?php

namespace Symfonier\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SymfonierApiBundle:Default:index.html.twig', array('name' => $name));
    }

    public function getDefaultAction()
    {
        return [];
    }
}
