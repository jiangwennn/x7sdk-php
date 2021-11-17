<?php

namespace X7\Module\X7Detection\Constant;

use X7\Constant\AbstractConstant;

/**
 * 对检测结果的处理类型
 */
class OperateType extends AbstractConstant
{

    /**
     * 拦截发送(禁止该消息发送并提示发送文本中含有敏感信息)
     */
    const BLOCK = 1;

    /**
     * 不展示(允许发送但实际上会拦截不展示)
     */
    const HIDE = 2;

    /**
     * 屏蔽关键词(屏蔽敏感词后剩余内容允许发送)
     */
    const FILTER = 3;

    /**
     * 其他
     */
    const OTHER = 4;

}