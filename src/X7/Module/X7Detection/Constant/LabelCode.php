<?php

namespace X7\Module\X7Detection\Constant;

use X7\Constant\AbstractConstant;

/**
 * 违规分类码
 */
class LabelCode extends AbstractConstant
{
    /**
     * 正常
     */
    const OK = 0;

    /**
     * 其他
     */
    const OTHER = 1;

    /**
     * 色情
     */
    const PORN = 2;

    /**
     * 广告
     */
    const AD = 3;

    /**
     * 暴恐
     */
    const VIOLENCE = 4;

    /**
     * 违禁
     */
    const PROHIBITED = 5;

    /**
     * 涉政
     */
    const POLITICAL = 6;

    /**
     * 谩骂
     */
    const ABUSE = 7;

    /**
     * 灌水
     */
    const NONSENSE = 8;

    /**
     * 违反广告法
     */
    const AD_LAW_VIOLATION = 9;

    /**
     * 涉价值观
     */
    const VALUE = 10;
}