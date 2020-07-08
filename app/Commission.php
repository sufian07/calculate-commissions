<?php
namespace App;

class Commission
{

    protected $transaction;

    public function __construct(
        Transaction $transaction,
        Utility $utility,
        Configuration $configuratoin
    ) {
        $this->transaction = $transaction;
        $this->utility = $utility;
        $this->configuratoin = $configuratoin;
    }

    public function calculate()
    {
        $currency = $this->transaction->getCurrency();
        $baseCurrency = $this->configuratoin->get('BASE_CURRENCY');
        $amount = $this->transaction->getAmount();
        $isEu = $this->isEu($this->transaction->getCountryAlpha2());
        $exchangeData = $this->utility->getDataFromUrl(
            $this->configuratoin->get('EXCHANGE_LOOKUP_URL')
        );
        $rates = $this->utility->getNestedData(
            $exchangeData, $this->configuratoin->get('EXCHANGE_FORMAT')
        );
        $rate = @$rates[$currency];
        if ($currency == $baseCurrency || $rate == 0) {
            $amntFixed = $amount;
        }
        if ($currency != $baseCurrency || $rate > 0) {
            $amntFixed = $amount / $rate;
        }
    
        return $this->utility->ceilToCent($amntFixed * ($isEu ? 0.01 : 0.02));
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