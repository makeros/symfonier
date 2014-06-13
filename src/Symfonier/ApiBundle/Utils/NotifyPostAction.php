<?php

namespace Symfonier\ApiBundle\Utils;

use Doctrine\ODM\MongoDB\PersistentCollection as PersistentCollection;

use Symfonier\ApiBundle\Document\Comment as Comment;
use Symfonier\ApiBundle\Document\Post as Post;

use Symfonier\ApiBundle\Utils\NotifyUtils as NotifyUtils;

class NotifyPostAction extends NotifyUtils
{

    protected $postDocument;
    protected $commentDocument;

    private $actionType;

    /**
     * 
     */
    private function defineActionType()
    {
        /*
         If comments are affected then the will be a PersistentCollection.
         So go on - save the notification!
         - arek
         */
        if($this->getDocument('commentDocument') instanceof Comment ){

            return 'comment';

        }

        if( $this->getDocument('postDocument') instanceof Post  )
        {
            switch ($this->getDocument('postDocument')->getType())
            {
                case 0:
                    return 'post';
                break;
                case 1:
                    return 'post_event';
                break;
                case 2:
                    return 'post_alarm';
                break;
            }

            return false;
        }
    }

    /**
     * @param Array $housingIds
     */
    private function getAllUsersFromHousingsById($housingIds)
    {
        $users = array();

        $housings = $this->dManager->createQueryBuilder('SymfonierApiBundle:Housing')
        ->field('id')->in($housingIds)
        ->getQuery()
        ->execute();

        foreach($housings as $housing)
        {
            $housingUsers = $housing->getUsers();

            foreach ($housingUsers as $u)
            {
                array_push($users, $u->getId());
            }
        }

        return $users;
    }

    private function getAllUsersForGroup($group)
    {
        $users = array();

        $groupUsers = $group->getUsers();

        foreach($groupUsers as $user)
        {
            $users[] = $user->getId();
        }

        return $users;
    }

    private function getTranslatedTextForActionType()
    {
        $text = array();

        switch ($this->actionType)
        {
            case 'post':
                $text['author'] = $this->getDocument('postDocument')->getUser()->getFirstName() .' '. $this->getDocument('postDocument')->getUser()->getLastName();
                $text['l_1'] =  'notify.new_post';
                $text['title'] = $this->getDocument('postDocument')->getTitle();
                return $text;
            break;
            case 'post_alarm':
                $text['author'] = $this->getDocument('postDocument')->getUser()->getFirstName() .' '. $this->getDocument('postDocument')->getUser()->getLastName();
                $text['l_1'] =  'notify.new_post_alarm';
                $text['title'] = substr($this->getDocument('postDocument')->getContent(), 0, 100).'...';
                return $text;
            break;
            case 'post_event':
                $text['author'] = $this->getDocument('postDocument')->getUser()->getFirstName() .' '. $this->getDocument('postDocument')->getUser()->getLastName();
                $text['l_1'] =  'notify.new_post_event';
                $text['title'] = $this->getDocument('postDocument')->getTitle();
                return $text;
            break;
            case 'comment':
                $text['author'] = $this->getDocument('commentDocument')->getUser()->getFirstName() .' '. $this->getDocument('commentDocument')->getUser()->getLastName();
                $text['l_1'] =  'notify.new_comment';
                $text['title'] = $this->getDocument('postDocument')->getTitle();
                return $text;
            break;
        }

        return 'no translations for post type';
    }
    /**
     * !!!!!! DUPLICATED with  notifyMessageAction!!!
     * @param Array $users - user to be notified
     * @param String $itemId
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
                'titleLangString' => $desc
            );
        }

        return $data;
    }

    private function getDataForPostNotify()
    {

        $users = array();

        $postDocument = $this->getDocument('postDocument');

        $postUserId = $postDocument->getUser()->getId();

        $itemId = $postDocument->getId();

        $desc = json_encode($this->getTranslatedTextForActionType());

        $housingIds = $postDocument->getHousingId();
        $group = $postDocument->getGroup();

        /*collect users*/
        if (!empty($housingIds))
        {
            $users = $this->getAllUsersFromHousingsById($housingIds);
        }

        if (!is_null($group))
        {
            /*TODO: get users for group!!! - arek*/
            $users = $this->getAllUsersForGroup($group);
        }

        if (empty($users))
        {
            return false;
        }

        /*remove post author*/
        if (($key = array_search($postUserId, $users)) !== false) {
            unset($users[$key]);
        }

        return $this->setUpNotifyData($users, $itemId, $desc);
    }

    /**
     * @return array $data
     */
    private function getDataForCommentNotify()
    {
        $data = array();

        $lastComment = $this->getDocument('commentDocument');

        $postOwnerUserId = $this->getDocument('postDocument')->getUser()->getId();

        $commentUserId = $lastComment->getUser()->getId();
        
        if(empty($lastComment)) return false;

        $desc = json_encode($this->getTranslatedTextForActionType());
        
        $itemId = $this->getDocument('postDocument')->getId().';'.$lastComment->getId();
        $type = 'comment';

        /* collect all users */
        $conversationUsers = array();
        $conversationUsers[] = $postOwnerUserId;

        foreach($this->getDocument('postDocument')->getComments() as $comment){

            $conversationUsers[] = $comment->getUser()->getId();

        }

        $users = array_unique($conversationUsers);
        
        if (($key = array_search($commentUserId, $users)) !== false) {
            unset($users[$key]);
        }

        return $this->setUpNotifyData($users, $itemId, $desc);
    }

    /**
     * @param String type
     */
    private function prepareDataForNotify()
    {
        switch ($this->actionType)
        {
            case 'comment':
                return $this->getDataForCommentNotify();
                break;

            case 'post':
            case 'post_event':
            case 'post_alarm':
                return $this->getDataForPostNotify();
                break;

            default:
                return false;
        }
    }

    public function __construct($dManager, $postDocument, $commentDocument)
    {


        $this->setDManager($dManager);
        $this->setDocument($postDocument,'postDocument');
        $this->setDocument($commentDocument, 'commentDocument');

        
        $this->actionType = $this->defineActionType();

        $notifyData = $this->prepareDataForNotify();

        if ( $notifyData && $this->actionType )
        {
            $this->addNotifications( $notifyData );
            
        }

        return $this;

    }


}