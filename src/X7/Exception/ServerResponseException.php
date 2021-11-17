<?php

namespace X7\Exception;

use Exception;

class ServerResponseException extends Exception
{

    protected $context;

    public function __construct($message, $context = "", $code = 0, $prev = null)
    {
        parent::__construct($message, $code, $prev);
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

}