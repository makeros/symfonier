<?php

namespace Symfonier\ApiBundle\Utils;

use Symfonier\ApiBundle\Document\Notify;
use Symfonier\ApiBundle\Utils\NotifyMongoLocker as NotifyMongoLocker;


class NotifyUtils
{
    protected $dManager;


    /**
     * Strings dict needed in front App
     */
    // private $notifyTypeDict = array(
    //     'comment' => 'notify.new_comment',
    //     'message' => 'notify.new_message',
    //     'messageReply' => 'notify.new_message_reply',
    //     // 'post' => 'notify.new_post'
    //     );

    /**
     * @param array $notifyData
     */
    protected function addNotifications($notifyData)
    {
        $locker = new NotifyMongoLocker($this->dManager);

        foreach($notifyData as $data)
        {

            /**
             * TODO: try to do this in one execute!
             * -arek
             */
            $query = $this->dManager->createQueryBuilder('SymfonierApiBundle:Notify')
            ->insert()
            ->field('itemId')->set($data['itemId'])
            ->field('userId')->set($data['userId'])
            ->field('type')->set($data['type'])
            ->field('titleLangString')->set($data['titleLangString'])
            ->field('createdAt')->set(new \MongoDate())

            ->field('viewedInTube')->set(false)
            ->field('isNew')->set(true)
            ->field('updatedInApp')->set(false)
            ->field('isRead')->set(false)
            ->field('countInNav')->set(true)

            ->getQuery()
            ->execute();

            $locker->unlock($data['userId']);
        }
        
    }

    private function getTypeLangString($type)
    {
        return $this->notifyTypeDict[$type];
    }

    public function __construct()
    {



    }

    public function getDManager()
    {
        return $this->dManager;
    }

    public function setDManager($dManager)
    {
        $this->dManager = $dManager;
    }

    public function getDocument($name)
    {
        return $this->$name;
    }

    public function setDocument($document, $name)
    {
        $this->$name = $document;
    }

}