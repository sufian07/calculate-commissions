<?php
namespace App;

class Commission
{

    protected $transaction;

    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
    }

    public function calculate()
    {
        $currency = $this->transaction->getCurrency();
        $amount = $this->transaction->getAmount();
        $isEu = $this->isEu($this->transaction->getCountryAlpha2());
        $exchangeData = json_decode(
            file_get_contents(Configuration::EXCHANGE_LOOKUP_URL), true
        );
        $rates = Utility::getNestedData(
            $exchangeData, Configuration::EXCHANGE_FORMAT
        );
        $rate = @$rates[$currency];
        if ($currency == Configuration::BASE_CURRENCY || $rate == 0) {
            $amntFixed = $amount;
        }
        if ($currency != Configuration::BASE_CURRENCY || $rate > 0) {
            $amntFixed = $amount / $rate;
        }
    
        return Utility::ceilToCent($amntFixed * ($isEu ? 0.01 : 0.02));
    }

    private function isEu($c)
    {
        switch($c) {
            case 'AT':
            case 'BE':
            case 'BG':
            case 'CY':
            case 'CZ':
            case 'DE':
            case 'DK':
            case 'EE':
            case 'ES':
            case 'FI':
            case 'FR':
            case 'GR':
            case 'HR':
            case 'HU':
            case 'IE':
            case 'IT':
            case 'LT':
            case 'LU':
            case 'LV':
            case 'MT':
            case 'NL':
            case 'PO':
            case 'PT':
            case 'RO':
            case 'SE':
            case 'SI':
            case 'SK':
                return true;
            default:
                return false;
        }
    }

}