<?php

namespace X7\Model;

use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use X7\Utils\Traits\BatchAssign;
use X7\Utils\Traits\ToArray;

/**
 * 基础Model
 */
abstract class Model
{
    use ToArray,BatchAssign;

    /**
     * 可选参数
     *
     * @var array
     */
    protected static $optionalFields = [];

    /**
     * 构造Model
     *
     * @param array $paramArr
     * @return static
     * @throws RuntimeException
     */
    public static function make($paramArr)
    {
        if (!is_array($paramArr)) {
            throw new RuntimeException("构造Model参数必须为数组");
        }
        $reflection = new ReflectionClass(static::class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);

        foreach ($properties as $property) {
            if (!isset($paramArr[$property->name]) && !in_array($property->name, static::$optionalFields)) {
                throw new RuntimeException("字段 {$property->name} 缺失 in paramArr");
            }
        }
        return (new static)->batchAssign($paramArr);
    }
}