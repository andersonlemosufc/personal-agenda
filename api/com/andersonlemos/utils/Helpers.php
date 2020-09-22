<?php
namespace com\andersonlemos\utils;

require_once __DIR__."/Constants.php";

use DateTime;

abstract class Helpers {

    public static function dateTimeToDefaultFormat($dateTime) {
        return is_null($dateTime) ? NULL : $dateTime->format(Constants::DEFAULT_DATE_FORMAT);
    }

    public static function stringToDateTime($stringDateTime) {
        return (is_null($stringDateTime) || $stringDateTime == "" )? NULL : new DateTime($stringDateTime);
    }

    public static function isAnIntegerValue($value) {
        return is_int($value) || (is_string($value) && is_numeric($value) && strval(intval($value)) == $value);
    }

}

?>
