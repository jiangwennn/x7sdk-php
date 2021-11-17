<?php

namespace X7\Exception;

use Exception;
use X7\Constant\ResponseCode;

class ParameterException extends Exception implements ApiExceptionInterface
{
    public function getResponseCode()
    {
        return ResponseCode::PARAM_ERR;
    }
}