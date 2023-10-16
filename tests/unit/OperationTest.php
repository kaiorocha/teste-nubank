<?php

namespace Tests;

use App\Entity\Operation;
use PHPUnit\Framework\TestCase;

/**
 *
 */
class OperationTest extends TestCase
{
    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testSell()
    {
        $sell = self::getMethod('sellOperation');

        $object = new \stdClass();
        $object->operation = "sell";
        $object->{"unit-cost"} = 10;
        $object->quantity = 10000;

        $entity = new Operation([$object]);

        $current_avg = $current_quantity = $performance = 0;

        $result = $sell->invokeArgs($entity, [(array) $object, &$performance, &$current_avg, &$current_quantity]);

        $this->assertArrayHasKey('tax', $result);
        $this->assertNotEmpty($result);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testBuy()
    {
        $sell = self::getMethod('buyOperation');

        $object = new \stdClass();
        $object->operation = "sell";
        $object->{"unit-cost"} = 10;
        $object->quantity = 10000;

        $entity = new Operation([$object]);

        $current_avg = $current_quantity = 0;

        $result = $sell->invokeArgs($entity, [(array) $object, &$current_avg, &$current_quantity]);

        $this->assertArrayHasKey('tax', $result);
        $this->assertNotEmpty($result);
    }

    /**
     * @return void
     * @throws \ReflectionException
     */
    public function testtaxCalculate()
    {
        $sell = self::getMethod('taxCalculate');

        $object = new \stdClass();
        $object->operation = "sell";
        $object->{"unit-cost"} = 10;
        $object->quantity = 10000;

        $entity = new Operation([$object]);

        $result = $sell->invoke($entity);

        $this->assertIsFloat($result);
    }

    /**
     * @param $name
     * @return \ReflectionMethod
     * @throws \ReflectionException
     */
    protected static function getMethod($name)
    {
        $class = new \ReflectionClass(Operation::class);
        $method = $class->getMethod($name);

        return $method;
    }
}