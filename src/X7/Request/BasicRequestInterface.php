<?php

namespace X7\Request;

use X7\Handler\ParamHandlerInterface;
use X7\Module\Basic\Model\BasicRequest;

/**
 * Basic Module 接入Request interface
 */
interface BasicRequestInterface
{
    /**
     * @param ParamHandlerInterface $paramHandler
     * @return static
     */
    public static function make(ParamHandlerInterface $paramHandler);

    /**
     * @return BasicRequest
     */
    public function getRequest();

}