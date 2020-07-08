<?php
namespace Test;

use App\Transaction;
use App\Utility;
use App\Configuration;
use App\PHPUnitUtil;
use Exception;
use PHPUnit\Framework\TestCase;

class TransactionTest extends TestCase
{
    protected function setUp(): void
    {
        $this->configuration = $this->getMockBuilder(Configuration::class)
            ->disableOriginalConstructor()
            ->setMethods(['get'])
            ->getMock();
        $this->configuration->method('get')
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
        $this->utility = $this->getMockBuilder(Utility::class)
            ->disableOriginalConstructor()
            ->setMethods(
                [
                    'getDataFromUrl',
                    'getNestedData'
                ]
            )->getMock();
        $this->utility->method('getDataFromUrl')
            ->will(
                $this->returnValue(          
                    PHPUnitUtil::getNestedArray(
                        "country.alpha2",
                        "GB"
                    )
                )
            );
        $this->utility->method('getNestedData')
            ->will(
                $this->returnValue("GB")
            );
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
        unset($this->utility);
    }

    public function testTransactionEmptyStringShouldFail()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Data missing");
        new Transaction('', $this->utility, $this->configuration);
    }

    public function testTransactionWrongStringShouldFail()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Invalid input');
        $transaction = new Transaction(
            '{"bin2":"4745030","amount":"2000.00","currency":"GBP"}',
            $this->utility,
            $this->configuration
        );
    }

    public function testTransactionShouldReturnValidData()
    {
        $transaction = new Transaction(
            '{"bin":"4745030","amount":"2000.00","currency":"GBP"}',
            $this->utility,
            $this->configuration
        );
        $this->assertEquals($transaction->getAmount(), 2000.0);
        $this->assertEquals($transaction->getBin(), 4745030);
        $this->assertEquals($transaction->getCurrency(), 'GBP');
        $this->assertEquals($transaction->getCountryAlpha2(), 'GB');
    }
}