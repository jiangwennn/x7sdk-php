<?php


namespace X7\Module\X7Detection\Request;

use RuntimeException;
use X7\Handler\ParamHandlerInterface;
use X7\Module\X7Detection\Constant\ApiMethod;
use X7\Request\RequestInterface;
use X7\Utils\ParamTool;

/**
 * 信息检测接口
 */
class MessageDetectRequest implements RequestInterface
{

    public $guid;

    public $detectionMessage;


    public function getApiMethod()
    {
        return ApiMethod::MESSAGE_DETECT;
    }

    public function setGuid($guid)
    {
        if (empty($guid) || !ParamTool::isIntegerNumber($guid)) {
            throw new RuntimeException("guid参数不正确");
        }
        $this->guid = $guid;
        return $this;
    }

    public function setDetectionMessage($detectionMessage)
    {
        if (empty($detectionMessage) || !is_string($detectionMessage)) {
            throw new RuntimeException("detectionMessage参数不正确");
        }
        $this->detectionMessage = $detectionMessage;
        return $this;
    }


    /**
     * @param ParamHandlerInterface $paramHandler
     * @return self
     */
    public static function make(ParamHandlerInterface $paramHandler)
    {
        return (new self)->setGuid($paramHandler->getInputValue("guid"))
            ->setDetectionMessage($paramHandler->getInputValue("detectionMessage"));
    }

}