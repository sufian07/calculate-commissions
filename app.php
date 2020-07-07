<?php
require_once __DIR__ . '/vendor/autoload.php';

foreach (explode("\n", file_get_contents($argv[1])) as $row) {

    if (empty($row)) {
        break;
    }
    try {
        $commission = new App\Commission(new App\Transaction($row));
        echo $commission->calculate() ."\n";
    } catch(Exception $ex) {
        echo 'error!';
        break;
    }
}