<?php

namespace X7\Utils\Traits;

use ReflectionClass;
use ReflectionProperty;

/**
 * 转数组trait
 *
 * @author jw
 * @since 2020-12-30
 */
trait ToArray
{
    public function toArray($filter = ReflectionProperty::IS_PUBLIC)
    {
        $reflectionClass = new ReflectionClass($this);
        $properties = $reflectionClass->getProperties($filter);
        $array = [];
        foreach ($properties as $prop) {
            $array[$prop->getName()] = $prop->getValue($this);
        }
        return $array;
    }
}