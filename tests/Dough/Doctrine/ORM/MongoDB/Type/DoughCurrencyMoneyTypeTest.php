<?php

use Dough\Doctrine\ODM\MongoDB\Type\DoughCurrencyMoneyType;
use Dough\Money\MultiCurrencyMoney;

class DoughCurrencyMoneyTypeTest extends PHPUnit_Framework_TestCase
{
    public function testConvertToDatabaseValue()
    {
        $money = new MultiCurrencyMoney(1234.567, 'BTC');
        $type = new DoughCurrencyMoneyType();
        $value = $type->convertToDatabaseValue($money);
        $this->assertSame('BTC:1234.567', $value);
    }

    public function testConvertToPHPValue()
    {
        $type = new DoughCurrencyMoneyType();
        $money = $type->convertToPHPValue('BTC:3.50');
        $this->assertInstanceOf('Dough\Money\MultiCurrencyMoney', $money);
        $this->assertSame(3.5, $money->getAmount());
    }

    public function testClosureToMongo()
    {
        $type = new DoughCurrencyMoneyType();
        $this->assertSame('$return = $value->getCurrency() . \':\' . (string) $value->getAmount();', $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = new DoughCurrencyMoneyType();
        $this->assertSame('$money = explode(\':\', $value);$return = new \Dough\Money\MultiCurrencyMoney($money[1], $money[0]);', $type->closureToPHP());
    }
}
