<?php

namespace Symfonier\ApiBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

/**
 * @MongoDB\Document
 */
class Test
{
    /**
     * @MongoDB\Id(strategy="auto")
     */
    protected $id;

    /**
     * @MongoDB\String
     */
    protected $msg;



    /**
     * Get id
     *
     * @return id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set msg
     *
     * @param string $msg
     * @return self
     */
    public function setMsg($msg)
    {
        $this->msg = $msg;
        return $this;
    }

    /**
     * Get msg
     *
     * @return string $msg
     */
    public function getMsg()
    {
        return $this->msg;
    }
}
