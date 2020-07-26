<?php
namespace com\andersonlemos\utils;

require_once __DIR__."/Constants.php";

abstract class Helpers {

    public static function dateTimeToDefaultFormat($dateTime) {
        return is_null($dateTime) ? NULL : $dateTime->format(Constants::DEFAULT_DATE_FORMAT);
    }

}

?>
