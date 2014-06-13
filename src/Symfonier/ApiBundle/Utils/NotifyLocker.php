<?php

namespace Symfonier\ApiBundle\Utils;


abstract class NotifyLocker
{

    private $lockCheckInterval;


    public function lock(){}

    public function unlock(){}

    public function waitForUnlock(){}

}