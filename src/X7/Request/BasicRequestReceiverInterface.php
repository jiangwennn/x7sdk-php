<?php

namespace X7\Request;

use X7\Request\Server\RequestParameterRetrieverInterface;
use X7\Exception\ApiExceptionInterface;

interface BasicRequestReceiverInterface
{
    /**
     * @param RequestParameterRetrieverInterface $retriever
     * @return static
     * @throws ApiExceptionInterface
     */
    public function validate(RequestParameterRetrieverInterface $retriever);
}