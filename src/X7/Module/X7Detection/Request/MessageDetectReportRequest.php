<?php

namespace X7\Module\X7Detection\Request;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\X7Detection\Constant\ApiMethod;
use X7\Module\X7Detection\Constant\OperateType;
use X7\Request\RequestInterface;

class MessageDetectReportRequest implements RequestInterface
{

    public $detectionLogId;

    public $operateType;

    public function getApiMethod()
    {
        return ApiMethod::MESSAGE_DETECT_REPORT;
    }

    public function setDetectionLogId($detectionLogId)
    {
        if (empty($detectionLogId) || !is_scalar($detectionLogId)) {
            throw new RuntimeException("detectionLogId参数错误");
        }
        $this->detectionLogId = $detectionLogId;
        return $this;
    }

    public function setOperateType($operateType)
    {
        if (!in_array($operateType, OperateType::all())) {
            throw new RuntimeException("operateType参数错误");
        }
        $this->operateType = $operateType;
        return $this;
    }


    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     */
    public static function make(ParamHandlerInterface $paramHandler)
    {
        return (new self)->setOperateType($paramHandler->getInputValue('operateType'))
            ->setDetectionLogId($paramHandler->getInputValue("detectionLogId"));
    }


}