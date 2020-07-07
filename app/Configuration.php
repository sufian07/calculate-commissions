<?php
namespace App;

use PHP_Token_FINAL;

class Configuration
{
    const BASE_CURRENCY = 'EUR';
    const BIN_LOOKUP_URL = 'https://lookup.binlist.net/';
    const BIN_FORMAT = "country.alpha2";

    const EXCHANGE_LOOKUP_URL = 'https://api.exchangeratesapi.io/latest';
    const EXCHANGE_FORMAT = "rates";
}