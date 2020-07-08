<?php
namespace Test;

use PHPUnit\Framework\TestCase;
use App\Commission;
use App\Transaction;
use App\Utility;
use App\Configuration;
use App\PHPUnitUtil;

class CommissionCalculateTest extends TestCase
{
    protected function setUp(): void
    {
        $this->configuration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $this->configuration->expects($this->atLeastOnce())->method('get')
            ->with(
                $this->logicalOr(
                    $this->equalTo('BASE_CURRENCY'),
                    $this->equalTo('BIN_LOOKUP_URL'),
                    $this->equalTo('BIN_FORMAT'),
                    $this->equalTo('EXCHANGE_LOOKUP_URL'),
                    $this->equalTo('EXCHANGE_FORMAT')
                )
            )
            ->will($this->returnCallback(array($this, 'configuration')));
    }
    public function configuration($key)
    {
        $constants  = [
            'BASE_CURRENCY' =>  'EUR',
            'BIN_LOOKUP_URL' => 'https://lookup.binlist.net/',
            'BIN_FORMAT' => "country.alpha2",
            'EXCHANGE_LOOKUP_URL' => 'https://api.exchangeratesapi.io/latest',
            'EXCHANGE_FORMAT' => "rates",
        ];
        return $constants[$key];
    }
    protected function tearDown(): void
    {
        unset($this->configuration);
    }

    public function testCalculateShouldReturnValidData100EurDk()
    {
        $transaction = $this->getMockBuilder(Transaction::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getBin',
                    'getAmount',
                    'getCurrency',
                    'getCountryAlpha2'
                ]
            )->getMock();
        $transaction->expects($this->once())->method('getAmount')
            ->will($this->returnValue(100.00));
        $transaction->expects($this->once())->method('getCurrency')
            ->will($this->returnValue("EUR"));
        $transaction->expects($this->once())->method('getCountryAlpha2')
            ->will($this->returnValue('DK'));
        $utility = $this->getMockBuilder(Utility::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getDataFromUrl',
                    'getNestedData'
                ]
            )->getMock();
        $utility->expects($this->once())->method('getDataFromUrl')
            ->will(
                $this->returnValue(          
                    PHPUnitUtil::getNestedArray(
                        'rates',
                        []
                    )
                )
            );
        $commission = new Commission($transaction, $utility, $this->configuration);
        $calculatedCommission = $commission->calculate();
        $this->assertEquals($calculatedCommission, 1);
    }

    public function testCalculateShouldReturnValidData50UsdLt()
    {
        $transaction = $this->getMockBuilder(Transaction::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getBin',
                    'getAmount',
                    'getCurrency',
                    'getCountryAlpha2'
                ]
            )->getMock();
        $transaction->expects($this->once())->method('getAmount')
            ->will($this->returnValue(50.00));
        $transaction->expects($this->once())->method('getCurrency')
            ->will($this->returnValue("USD"));
        $transaction->expects($this->once())->method('getCountryAlpha2')
            ->will($this->returnValue('LT'));
        $utility = $this->getMockBuilder(Utility::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getDataFromUrl',
                    'getNestedData'
                ]
            )->getMock();
        $utility->expects($this->once())->method('getDataFromUrl')
            ->will(
                $this->returnValue(          
                    PHPUnitUtil::getNestedArray(
                        'rates',
                        ['USD'=> 1.129]
                    )
                )
            );
        $utility->expects($this->once())->method('getNestedData')
            ->will(
                $this->returnValue(['USD'=> 1.129])
            );
        $commission = new Commission($transaction, $utility, $this->configuration);
        $calculatedCommission = $commission->calculate();
        $this->assertEquals($calculatedCommission, 0.45);
    }
}