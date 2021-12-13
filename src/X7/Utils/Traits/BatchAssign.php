<?php

namespace X7\Utils\Traits;

use ReflectionClass;

/**
 * 批量赋值trait
 *
 * @author jw
 * @since 2020-12-30
 */
trait BatchAssign
{

    public function batchAssign($data)
    {
        $instance = clone $this;
        $reflectionClass = new ReflectionClass($instance);
        $reflectionClass->getProperties();
        foreach ($data as $key => $value) {
            $instance->{$key} = $value;
        }
        return $instance;
    }

}