<?php
namespace App;

use Exception;

class Transaction
{
    protected $bin;
    protected $amount;
    protected $currency;
    protected $countryAlpha2;

    public function __construct(
        string $row,
        Utility $utility,
        Configuration $configuratoin
    ) {
        $this->utility = $utility;
        $this->configuratoin = $configuratoin;
        try {
            $data = json_decode($row, true);
            $this->bin = (int)trim($data['bin']);
            $this->amount = (float)trim($data['amount']);
            $this->currency = trim($data['currency']);
        } catch (Exception $ex) {
            throw new Exception('Invalid input');
        }
        if (!isset($this->bin)
            || !isset($this->amount) 
            || !isset($this->currency)
            || !$this->currency
        ) {
            throw new Exception('Data missing');
        }

        $binResultsArray = $this->utility->getDataFromUrl(
            $this->configuratoin->get('BIN_LOOKUP_URL') . $this->bin
        );
        $this->countryAlpha2 = $this->utility->getNestedData(
            $binResultsArray, $this->configuratoin->get('BIN_FORMAT')
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