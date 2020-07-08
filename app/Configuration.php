<?php
namespace App;

class Configuration
{
    private $_constants  = [
        'BASE_CURRENCY' =>  'EUR',
        'BIN_LOOKUP_URL' => 'https://lookup.binlist.net/',
        'BIN_FORMAT' => "country.alpha2",
        'EXCHANGE_LOOKUP_URL' => 'https://api.exchangeratesapi.io/latest',
        'EXCHANGE_FORMAT' => "rates",
    ];

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
            self::$_instance = new Configuration();
        }
    
        return self::$_instance;
    }

    public function get($key)
    {
        return $this->_constants[$key];
    }
}