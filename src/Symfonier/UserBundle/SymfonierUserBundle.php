<?php

namespace Symfonier\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SymfonierUserBundle extends Bundle
{
   /**
     * Here we are overiding the FOSUser templates 
     * - arek
     */
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
