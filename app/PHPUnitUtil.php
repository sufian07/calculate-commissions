<?php
namespace App;

class PHPUnitUtil
{
    /**
     * Call protected/private method of a class. Intended to use in phpunit
     * for testing purpose only. Do not use this code in production.
     *
     * @param object $object     Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public static function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public static function getNestedArray($format, $value)
    {
        $arr = [];
        $temp = &$arr;
        $keyArray = explode(".", $format);
        $count = count($keyArray);
        for ($i=0; $i < $count; $i++) {
            $key = $keyArray[$i];
            if ($i == $count-1) {
                $temp[$key] = $value;
            } else {
                $temp[$key] = [];
            }
            $temp = &$temp[$key];
        }
        return $arr;
    }
}