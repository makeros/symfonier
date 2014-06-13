<?php

namespace Acme\DemoBundle\EventListener;

use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener
{
    protected $session;

    public function __construct($session)
    {
        $this->session = $session;
    }

    public function onKernelController(GetResponseEvent $event)
    {
        $this->session->set('User');
        // $this->session->remove('User');

        if ($event->isMasterRequest) 
        {
            
            if( $this->session->has('User'))
            {
                echo 'Master with User!';
                
            }        
        }
    }
}
