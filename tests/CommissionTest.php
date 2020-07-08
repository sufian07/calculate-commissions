<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use App\Commission;
use App\PHPUnitUtil;

class CommissionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->blankConfiguration = $this->createMock('App\Configuration');
        $this->utility = $this->createMock('App\Utility');
        $this->transaction = $this->createMock('App\Transaction');
        $this->blankCommission = new Commission(
            $this->transaction,
            $this->utility,
            $this->blankConfiguration
        );
    }
    protected function tearDown(): void
    {
        unset($this->utility);
        unset($this->transaction);
        unset($this->blankCommission);
        unset($this->blankConfiguration);
    }

    public function testIsEuShouldReturnFalseForEmptyString()
    {
        $isEu = PHPUnitUtil::invokeMethod($this->blankCommission, 'isEu', array(''));
        $this->assertFalse($isEu);
    }

    public function testIsEuShouldReturnFalseIfCurrencyNotMatched()
    {
        $isEu = PHPUnitUtil::invokeMethod($this->blankCommission, 'isEu', array('BD'));
        $this->assertFalse($isEu);
    }

    public function testIsEuShouldReturnTrueIfCurrencyMatched()
    {
        $isEu = PHPUnitUtil::invokeMethod($this->blankCommission, 'isEu', array('AT'));
        $this->assertTrue($isEu);
    }
}