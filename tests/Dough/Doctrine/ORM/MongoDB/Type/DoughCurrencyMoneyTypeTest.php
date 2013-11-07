<?php

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyType;
use Dough\Money\MultiCurrencyMoney;

class DoughCurrencyMoneyTypeTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Type::registerType('dough_currency_money', 'Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyType');
    }

    public function testConvertToDatabaseValue()
    {
        $money = new MultiCurrencyMoney(1234.567, 'BTC');
        $type = Type::getType('dough_currency_money');
        $value = $type->convertToDatabaseValue($money);
        $this->assertSame('BTC:1234.567', $value);
    }

    public function testConvertToPHPValue()
    {
        $type = Type::getType('dough_currency_money');
        $money = $type->convertToPHPValue('BTC:3.50');
        $this->assertInstanceOf('Dough\Money\MultiCurrencyMoney', $money);
        $this->assertSame(3.5, $money->getAmount());
    }

    public function testClosureToMongo()
    {
        $type = Type::getType('dough_currency_money');
        $this->assertSame('$return = $value->getCurrency() . \':\' . (string) $value->getAmount();', $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = Type::getType('dough_currency_money');
        $this->assertSame('$money = explode(\':\', $value);$return = new \Dough\Money\MultiCurrencyMoney($money[1], $money[0]);', $type->closureToPHP());
    }
}
