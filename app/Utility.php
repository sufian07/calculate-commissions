<?php
namespace App;

use Exception;

class Utility
{
    private static $_instance = null;
    
    // The constructor is private
    // to prevent initiation with outer code.
    private function __construct()
    {
        // The expensive process (e.g.,db connection) goes here.
    }

    // The object is created from within the class itself
    // only if the class has no instance.
    public static function getInstance()
    {
        if (self::$_instance == null) {
            self::$_instance = new Utility();
        }
    
        return self::$_instance;
    }

    public function getNestedData(array $data, string $format)
    {
        $value = $data;
        foreach (explode(".", $format) as $key) {
            $value = $value[$key];
        }
        return $value;
    }

    public function ceilToCent(float $amount)
    {
        return ceil($amount * 100)/100;
    }

    public function getDataFromUrl(string $url)
    {
        try {
            $data =  json_decode(
                file_get_contents($url), true
            );
            if (!$data) {
                throw new Exception();
            }
            return $data;
        } catch(Exception $ex) {
            throw new Exception('Getting data from '.$url.' has been failed');
        }
    }
}