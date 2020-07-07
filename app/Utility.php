<?php
namespace App;

class Utility
{
    public static function getNestedData(array $data, string $format)
    {
        $value = $data;
        foreach (explode(".", $format) as $key) {
            $value = $value[$key];
        }
        return $value;
    }

    public static function ceilToCent(float $amount)
    {
        return ceil($amount * 100)/100;
    }
}