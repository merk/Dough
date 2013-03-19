<?php

use Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyType;
use Dough\Money\Money;

class DoughMoneyTypeTest extends PHPUnit_Framework_TestCase
{
    public function testConvertToDatabaseValue()
    {
        $money = new Money(1234.567);
        $type = new DoughMoneyType();
        $value = $type->convertToDatabaseValue($money);
        $this->assertSame('1234.567', $value);

    }

    public function testConvertToPHPValue()
    {
        $type = new DoughMoneyType();
        $money = $type->convertToPHPValue('3.50');
        $this->assertInstanceOf('Dough\Money\Money', $money);
        $this->assertSame(3.5, $money->getAmount());
    }

    public function testClosureToMongo()
    {
        $type = new DoughMoneyType();
        $this->assertSame('$return = (string) $value->getAmount();', $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = new DoughMoneyType();
        $this->assertSame('$return = new \Dough\Money\Money($value);', $type->closureToPHP());
    }
}
