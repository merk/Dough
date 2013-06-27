<?php

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Money\MultiCurrencyMoney;
use Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyHashType;

class DoughCurrencyMoneyHashTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Type::registerType('dough_currency_money', 'Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyType');
    }

    public function testConvertToDatabaseValue()
    {
        $type = new DoughCurrencyMoneyHashType();

        $startingValue = array(
            'value1' => null,
            'value2' => new MultiCurrencyMoney(1234.567, 'BTC')
        );
        $expectedValue = array(
            'value1' => null,
            'value2' => 'BTC:1234.567'
        );
        $value = $type->convertToDatabaseValue($startingValue);
        $this->assertEquals($expectedValue, $value);
    }

    public function testConvertToPHPValue()
    {
        $type = new DoughCurrencyMoneyHashType();
        $startingValue = array(
            'value1' => null,
            'value2' => 'BTC:1234.567'
        );
        $expectedValue = array(
            'value1' => null,
            'value2' =>new MultiCurrencyMoney(1234.567, 'BTC')
        );
        $value = $type->convertToPHPValue($startingValue);
        $this->assertEquals($expectedValue, $value);
    }

    public function testClosureToMongo()
    {
        $type = new DoughCurrencyMoneyHashType();
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $return = $value->getCurrency() . \':\' . (string) $value->getAmount();$process[$key] = $return; } } $return = $process;';
        $this->assertSame($expected, $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = new DoughCurrencyMoneyHashType();
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $money = explode(\':\', $value);$return = new \Dough\Money\MultiCurrencyMoney($money[1], $money[0]);$process[$key] = $return; } } $return = $process;';

        $this->assertSame($expected, $type->closureToPHP());
    }
}
