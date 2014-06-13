<?php

namespace Symfonier\ApiBundle\Document;



/**
 * Symfonier\ApiBundle\Document\Test
 */
class Test
{
    /**
     * @var $id
     */
    protected $id;

    /**
     * @var string $msg
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