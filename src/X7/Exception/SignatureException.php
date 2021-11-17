<?php

namespace X7\Exception;

use Exception;
use X7\Constant\ResponseCode;

class SignatureException extends Exception implements ApiExceptionInterface
{

    public function getResponseCode()
    {
        return ResponseCode::SIGN_ERR;
    }

}