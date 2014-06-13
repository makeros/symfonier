<?php

namespace Symfonier\UserBundle\EventListener;

use Symfony\Component\DependencyInjection\ContainerInterface;  
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use FOS\UserBundle\Model\UserInterface; 

class InteractiveLogin
{
    protected $session;  
  
    public function __construct($session)  
    {  
        $this->session = $session;  

    }  
      
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)  
    {  
        $toke = $event->getAuthenticationToken();  
        
        if ($user->getUser() instanceof UserInterface) {  
            
     
           
        }  
    }

    public function onKernelResponse(FilterResponseEvent $event)
    {
        // $user = $event->getAuthenticationToken()->getUser();  
        
        
        // var_dump('user logged response');

        // var_dump($event);
        // var_dump($event->getRequest()->getSession());
        // exit();
        // if ($user instanceof UserInterface) {  
        // }  
    }  
}
