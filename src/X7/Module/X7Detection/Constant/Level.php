<?php

namespace X7\Module\X7Detection\Constant;

use X7\Constant\AbstractConstant;

/**
 * 检查结果的分类级别
 */
class Level extends AbstractConstant
{

    /**
     * 通过
     */
    const PASS = 1;

    /**
     * 不通过
     */
    const VIOLATION = -1;

}