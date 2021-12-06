<?php

namespace X7\Module\X7Detection\Model;

use ReflectionClass;
use ReflectionProperty;
use RuntimeException;
use X7\Model\Model;

class DetectResult extends Model
{

    /**
     * 检查结果的ID
     *
     * @var string
     */
    public $detectionLogId;

    /**
     * 检查结果的分类级别
     *
     * @var string
     */
    public $level;

    /**
     * 违规分类码
     *
     * @var string
     */
    public $labelCode;

    /**
     * 敏感词数组
     *
     * @var array
     */
    public $sensitiveWords;

    /**
     * 构造Model
     *
     * @param array $paramArr
     * @return self
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
            } elseif ($property->name == "sensitiveWords") {
                if (!is_array($paramArr[$property->name])) {
                    throw new RuntimeException("字段 {$property->name} 类型有误 in paramArr");
                }
            } elseif (!empty($paramArr[$property->name]) && !is_scalar($paramArr[$property->name])) {
                throw new RuntimeException("字段 {$property->name} 类型有误 in paramArr");
            }
        }
        return (new static)->batchAssign($paramArr);
    }
}