<?php

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Money\Money;
use Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyHashType;

class DoughMoneyHashTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Type::registerType('dough_money', 'Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyType');
        Type::registerType('dough_money_hash', 'Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyHashType');
    }

    public function testConvertToDatabaseValue()
    {
        $type = Type::getType('dough_money_hash');
        $startingValue = array(
            'value1' => null,
            'value2' =>new Money(1234.567, 'BTC')
        );
        $expectedValue = array(
            'value1' => null,
            'value2' => '1234.567'
        );
        $value = $type->convertToDatabaseValue($startingValue);
        $this->assertEquals($expectedValue, $value);
    }

    public function testConvertToPHPValue()
    {
        $type = Type::getType('dough_money_hash');
        $startingValue = array(
            'value1' => null,
            'value2' => '1234.567'
        );
        $expectedValue = array(
            'value1' => null,
            'value2' =>new Money(1234.567)
        );
        $value = $type->convertToPHPValue($startingValue);
        $this->assertEquals($expectedValue, $value);
    }

    public function testClosureToMongo()
    {
        $type = Type::getType('dough_money_hash');
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $return = (string) $value->getAmount();$process[$key] = $return; } } $return = $process;';
        $this->assertSame($expected, $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = Type::getType('dough_money_hash');
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $return = new \Dough\Money\Money($value);$process[$key] = $return; } } $return = $process;';

        $this->assertSame($expected, $type->closureToPHP());
    }
}
