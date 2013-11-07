<?php

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Money\MultiCurrencyMoney;
use Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyHashType;

class DoughCurrencyMoneyHashTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Type::registerType('dough_currency_money', 'Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyType');
        Type::registerType('dough_currency_money_hash', 'Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyHashType');
    }

    public function testConvertToDatabaseValue()
    {
        $type = Type::getType('dough_currency_money_hash');
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
        $type = Type::getType('dough_currency_money_hash');
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
        $type = Type::getType('dough_currency_money_hash');
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $return = $value->getCurrency() . \':\' . (string) $value->getAmount();$process[$key] = $return; } } $return = $process;';
        $this->assertSame($expected, $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = Type::getType('dough_currency_money_hash');
        $expected = '$process = $value;foreach ($process as $key => $value) { if ($value) { $money = explode(\':\', $value);$return = new \Dough\Money\MultiCurrencyMoney($money[1], $money[0]);$process[$key] = $return; } } $return = $process;';

        $this->assertSame($expected, $type->closureToPHP());
    }
}
