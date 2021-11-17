<?php

namespace X7\Module\X7mall\Constant;

use X7\Constant\AbstractConstant;

class ApiMethod extends AbstractConstant
{

    /**
     * 道具发放
     */
    const PROP_ISSUE = 'x7mall.propIssue';

    /**
     * 道具查询
     */
    const PROP_QUERY = 'x7mall.propQuery';

    /**
     * 角色查询
     */
    const ROLE_QUERY = 'x7mall.roleQuery';

    /**
     * 商城入口
     */
    const MALL_ENTRY = 'x7mall.mallEntry';

    /**
     * 购买成功通知
     */
    const ORDER_NOTIFY = 'x7mall.orderNotify';
}
