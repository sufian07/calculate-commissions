<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use App\Commission;
use App\Transaction;
use App\PHPUnitUtil;

class CommissionTest extends TestCase
{
    protected $input = '{"bin":"45717360","amount":"100.00","currency":"EUR"}';

    public function testIsEuShouldReturnFalseForEmptyString()
    {
        $commission = new Commission(new Transaction($this->input));
        $isEu = PHPUnitUtil::invokeMethod($commission, 'isEu', array(''));
        $this->assertFalse($isEu);
    }

    public function testIsEuShouldReturnFalseIfCurrencyNotMatched()
    {
        $commission = new Commission(new Transaction($this->input));
        $isEu = PHPUnitUtil::invokeMethod($commission, 'isEu', array('BD'));
        $this->assertFalse($isEu);
    }

    public function testIsEuShouldReturnTrueIfCurrencyMatched()
    {
        $commission = new Commission(new Transaction($this->input));
        $isEu = PHPUnitUtil::invokeMethod($commission, 'isEu', array('AT'));
        $this->assertTrue($isEu);
    }
}