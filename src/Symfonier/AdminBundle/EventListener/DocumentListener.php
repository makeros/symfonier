<?php

namespace Symfonier\AdminBundle\EventListener;

use Doctrine\ODM\MongoDB\Event\LifecycleEventArgs;
use Symfony\Component\Form\Exception\InvalidArgumentException;


class DocumentListener
{
    private $dManager;


    public function postPersist(LifecycleEventArgs $eventArgs)
    {

    }

    public function prePersist(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        $this->dManager = $eventArgs->getDocumentManager();

    }

    public function postUpdate(LifecycleEventArgs $eventArgs)
    {
        $document = $eventArgs->getDocument();

        $this->dManager = $eventArgs->getDocumentManager();

    }

}