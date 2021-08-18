<?php


namespace rednaoformpdfbuilder\Utils;


class Sanitizer
{
    public static function SanitizeString($value)
    {
        if($value==null)
            return '';

        if(is_array($value))
            return '';

        return strval($value);
    }

    public static function SanitizeBoolean($value,$defaultValue=false)
    {
        if($value==null)
            return $defaultValue;

        return $value==true;

    }

}