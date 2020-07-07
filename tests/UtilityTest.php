<?php
namespace Test;

use App\Utility;
use PHPUnit\Framework\TestCase;

class UtilityTest extends TestCase
{
    public function test()
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
}