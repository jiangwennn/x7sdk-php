<?php

namespace X7\Constant;

use ReflectionClass;

abstract class AbstractConstant
{

    public static function all()
    {
        $className = get_called_class();
        $reflectionClass = new ReflectionClass($className);
        return $reflectionClass->getConstants();
    }

}