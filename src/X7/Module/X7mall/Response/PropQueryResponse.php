<?php

namespace X7\Module\X7mall\Response;

use RuntimeException;
use X7\Module\X7mall\Constant\ApiMethod as X7mallApiMethod;
use X7\Module\X7mall\Model\Prop;
use X7\Response\CommonResponse;

/**
 * 道具查询请求响应
 */
class PropQueryResponse extends CommonResponse
{
    /**
     * @var Prop[]
     */
    public $props = [];


    protected $apiMethod = X7mallApiMethod::PROP_QUERY;


    public function setProps($props)
    {
        $getProps = is_object($props) ? [$props] : $props;

        foreach ($getProps as $prop) {
            if (!($prop instanceof Prop)) {
                throw new RuntimeException("props数组内元素类型必须为 X7\Module\Model\X7mall\Prop");
            }
        }
        $this->props = $getProps;
        return $this;
    }

    public function appendProp(Prop $prop)
    {
        $this->props[] = $prop;
        return $this;
    }

}