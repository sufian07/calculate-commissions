<?php
namespace App;

use Exception;

class Transaction
{
    protected $bin;
    protected $amount;
    protected $currency;
    protected $countryAlpha2;

    public function __construct(string $row)
    {
        $keyValue = explode(",", $row);
        $binArray = explode(':', $keyValue[0]);
        $this->bin = trim($binArray[1], '"');

        $amountArray = explode(':', $keyValue[1]);
        $this->amount = trim($amountArray[1], '"');

        $currencyArray = explode(':', $keyValue[2]);
        $this->currency = trim($currencyArray[1], '"}');
    
        $binResults = file_get_contents(Configuration::BIN_LOOKUP_URL. $this->bin);
        if (!$binResults) {
            throw new Exception('Binary lookup has been failed');
        }
        $binResultsArray = json_decode($binResults, true);
        $this->countryAlpha2 = Utility::getNestedData(
            $binResultsArray, Configuration::BIN_FORMAT
        );
    }

    public function getBin()
    {
        return $this->bin;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function getCountryAlpha2()
    {
        return $this->countryAlpha2;
    }    
}