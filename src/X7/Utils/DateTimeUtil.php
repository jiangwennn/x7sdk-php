<?php

namespace X7\Utils;


/**
 * 日期助手
 */
class DateTimeUtil
{

    public static function getDateTimeFormat()
    {
        return DATE_ISO8601;
    }

    public static function getTimeStr($time = null)
    {
        return date(self::getDateTimeFormat(), !empty($time) ? $time : time());
    }

    public static function isValidTimeStr($timeStr)
    {
        $timestamp = strtotime($timeStr);
        return !empty($timestamp);
    }


    public static function timeDateDiff($timeStart, $timeEnd)
    {
        $dateStart = date_create(date("Y-m-d",$timeStart));
        $dateEnd = date_create(date("Y-m-d", $timeEnd));
        if (!$dateStart || !$dateEnd) {
            return -1;
        }
        $dateDiff = date_diff($dateStart, $dateEnd);
        return $dateDiff->days;
    }

}