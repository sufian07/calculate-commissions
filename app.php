<?php

use App\Configuration;
use App\Utility;

require_once __DIR__ . '/vendor/autoload.php';

foreach (explode("\n", file_get_contents($argv[1])) as $row) {

    if (empty($row)) {
        break;
    }
    try {
        $commission = new App\Commission(
            new App\Transaction(
                $row,
                Utility::getInstance(),
                Configuration::getInstance()
            ),
            Utility::getInstance(),
            Configuration::getInstance()
        );
        echo $commission->calculate() ."\n";
    } catch(Exception $ex) {
        echo $ex->getMessage();
        break;
    }
}