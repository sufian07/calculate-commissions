<?php
namespace Test;

use App\Configuration;
use App\Utility;
use App\PHPUnitUtil;
use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
    public function testGetNestedDataShouldReturnCorrectData()
    {
        $data = [
            [
                'name'  => 'Rafiq',
                'age'   => 30,
                'address' => [
                    'country' => 'Bangladesh'
                ]
            ]
        ];
        $name = Utility::getNestedData($data, "0.name");
        $age = Utility::getNestedData($data, "0.age");
        $country = Utility::getNestedData($data, "0.address.country");

        $this->assertEquals($name, "Rafiq");
        $this->assertEquals($age, 30);
        $this->assertEquals($country, "Bangladesh");
    }

    public function testCeilToCentShouldReturnCorrectData()
    {
        $this->assertEquals(Utility::ceilToCent(1.6423057973395), 1.65);
        $this->assertEquals(Utility::ceilToCent(2.2958057395143), 2.3);
        $this->assertEquals(Utility::ceilToCent(44.196453234628), 44.2);
        $this->assertEquals(Utility::ceilToCent(0.46180), 0.47);
    }

    public function testGetNestedArray()
    {
        $this->assertEquals(
            PHPUnitUtil::getNestedArray(
                'rates',
                ['EUR'=> 1.5]
            ),
            [
                'rates'=>['EUR'=> 1.5]
            ]
        );
        $this->assertEquals(
            PHPUnitUtil::getNestedArray(
                'a.b.c.d.e', 5
            ),
            [
                'a'=>[
                    'b'=>[
                        'c'=>[
                            'd'=>[
                                'e'=>5
                            ]
                        ]
                    ]
                ]
            ]
        );
    }
}