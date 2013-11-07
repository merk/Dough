<?php

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyType;
use Dough\Money\Money;

class DoughMoneyTypeTest extends PHPUnit_Framework_TestCase
{
    public function __construct()
    {
        Type::registerType('dough_money', 'Dough\Doctrine\ODM\MongoDB\Type\DoughMoneyType');
    }

    public function testConvertToDatabaseValue()
    {
        $money = new Money(1234.567);
        $type = Type::getType('dough_money');
        $value = $type->convertToDatabaseValue($money);
        $this->assertSame('1234.567', $value);

    }

    public function testConvertToPHPValue()
    {
        $type = Type::getType('dough_money');
        $money = $type->convertToPHPValue('3.50');
        $this->assertInstanceOf('Dough\Money\Money', $money);
        $this->assertSame(3.5, $money->getAmount());
    }

    public function testClosureToMongo()
    {
        $type = Type::getType('dough_money');
        $this->assertSame('$return = (string) $value->getAmount();', $type->closureToMongo());
    }

    public function testClosureToPHP()
    {
        $type = Type::getType('dough_money');
        $this->assertSame('$return = new \Dough\Money\Money($value);', $type->closureToPHP());
    }
}
