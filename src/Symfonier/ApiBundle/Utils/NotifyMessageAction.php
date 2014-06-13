<?php

namespace Symfonier\ApiBundle\Utils;

use Symfonier\ApiBundle\Document\Message as Message;

use Symfonier\ApiBundle\Utils\NotifyUtils as NotifyUtils;

class NotifyMessageAction extends NotifyUtils
{

    protected $messageDocument;

    private $actionType;

    /**
     * @return String type
     */
    private function defineActionType()
    {
        $messageDocument = $this->getDocument('messageDocument');

        if (is_null($messageDocument->getThread()))
        {
            return 'message';
        }

        if (!is_null($messageDocument->getThread()))
        {
            return 'messageReply';
        }

        return false;
    }

    /**
     * @param String $messageId
     * @return Object $message
     */
    private function findMessageById($messageId)
    {

        return $this->dManager->getRepository('SymfonierApiBundle:Message')
        ->findOneBy(
            array('id' => new \MongoId($messageId) )
        );

    }


    /**
     * @param Array $users - user to be notified
     * @param String $itemId
     * TODO: should be moved to notifyUtils - arek
     */
    private function setUpNotifyData($users, $itemId, $desc)
    {
        $data = array();

        /* fill the data*/
        foreach ($users as $userId) {

            $data[] = array(
                'userId' => $userId,
                'itemId' => $itemId,
                'type' => $this->actionType,
                'titleLangString' =>$desc
            );
        }

        return $data;
    }

    private function getDataForReplyNotify()
    {
        $threadDocument = $this->getDocument('messageDocument');

        $mainMessage = $this->findMessageById($threadDocument->getThread());

        $recipients = $mainMessage->getRecipients();

        $threadUserId = $threadDocument->getUser()->getId();
        $mainUserId = $mainMessage->getUser()->getId();



        $desc['author'] = $threadDocument->getUser()->getFirstName() .' '. $threadDocument->getUser()->getLastName();
        $desc['l_1'] =  'notify.new_message_reply';
        $desc['title'] = $mainMessage->getTitle();

        $desc = json_encode($desc);



        $recipientsUsers = array();

        $recipientsUsers[] = $mainUserId;

        foreach($recipients as $recipient){

            $recipientsUsers[] = $recipient->getId();

        }
        
        $users = array_unique($recipientsUsers);

        $users = array_diff($users, array($threadUserId));

        $itemId = $mainMessage->getId().';'.$threadDocument->getId();

        return $this->setUpNotifyData($users, $itemId, $desc);
    }

    /**
     * @return array $data
     */
    private function getDataForMessageNotify()
    {

        $messageDocument = $this->getDocument('messageDocument');

        $recipients = $messageDocument->getRecipients();
        
        $itemId = $messageDocument->getId();

        $desc['author'] = $messageDocument->getUser()->getFirstName() .' '. $messageDocument->getUser()->getLastName();
        $desc['l_1'] =  'notify.new_message';
        $desc['title'] = $messageDocument->getTitle();

        $desc = json_encode($desc);

        /* collect all users */
        $recipientsUsers = array();

        foreach($recipients as $recipient){

            $recipientsUsers[] = $recipient->getId();

        }

        $users = array_unique($recipientsUsers);

        return $this->setUpNotifyData($users ,$itemId, $desc);
    }

    /**
     * @param String type
     */
    private function prepareDataForNotify()
    {
        switch ($this->actionType)
        {
            case 'message':
                return $this->getDataForMessageNotify();
                break;

            case 'messageReply':
                return $this->getDataForReplyNotify();
                break;

            default:
                return false;
        }
    }

    public function __construct($dManager, $messageDocument)
    {


        $this->setDManager($dManager);
        $this->setDocument($messageDocument,'messageDocument');
        
        $this->actionType = $this->defineActionType();

        $notifyData = $this->prepareDataForNotify();

        if ( $notifyData )
        {
            $this->addNotifications( $notifyData );
            
        }

        return $this;

    }

}