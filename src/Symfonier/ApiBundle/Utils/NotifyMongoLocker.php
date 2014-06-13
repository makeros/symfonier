<?php

namespace Symfonier\ApiBundle\Utils;

use Symfonier\ApiBundle\Document\NotifyLocker;

use Symfony\Component\HttpFoundation\Response;


class NotifyMongoLocker extends NotifyLocker
{

    private $dManager;

    private $loopCounter = 0;
    private $loopGoal = 30;

    private $lasUpdate = 0;

    private $lockCheckInterval = 2;

    private function isLockInDB($userId, $lastUpdate)
    {
        return ( $this->isLocked($userId, $lastUpdate) )? true : false;
    }

    private function setLock($userId, $lastUpdate)
    {

        if ($this->isLocked($userId, $lastUpdate)) return;

        $this->dManager->createQueryBuilder('SymfonierApiBundle:NotifyLocker')
        ->insert()
        ->field('userId')->set($userId)
        ->field('lastUpdate')->set($lastUpdate)
        ->getQuery()
        ->execute();

    }

    private function isLocked($userId, $lastUpdate)
    {
        $lock = $this->dManager->createQueryBuilder('SymfonierApiBundle:NotifyLocker')
        ->field('userId')->equals($userId)
        ->field('lastUpdate')->equals($lastUpdate)
        ->getQuery()
        ->execute()
        ->toArray();

        if (count($lock) > 0 ) return true;

        return false;
    }

    private function removeLock($userId)
    {
        $this->dManager->createQueryBuilder('SymfonierApiBundle:NotifyLocker')
        ->remove()
        ->field('userId')->equals($userId)
        ->getQuery()
        ->execute();

    }


    public function __construct($dManager)
    {

        $this->dManager = $dManager;

    }

    public function getLastUpdate()
    {
        return $this->lastUpdate;
    }

    public function lock($userId)
    {

        $md = new \MongoDate();
        $this->lastUpdate = $md->sec.'_'.$md->usec;
        $this->setLock($userId, $this->lastUpdate);

        return $this->lastUpdate;

    }

    public function unlock($userId)
    {

        $this->removeLock($userId);
    }

    public function waitForUnlock($userId, $lastUpdate)
    {

        session_write_close();
        set_time_limit(120);

        while ( $this->isLockInDB($userId, $lastUpdate) )
        {

            if($this->loopCounter == $this->loopGoal)
            {
                
                return false;
            }

            sleep($this->lockCheckInterval);
            $this->loopCounter++;

        }


        return true;

    }


}