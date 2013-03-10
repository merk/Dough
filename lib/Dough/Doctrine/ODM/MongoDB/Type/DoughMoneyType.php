<?php
namespace Dough\Doctrine\ODM\MongoDB\Type;

use Doctrine\ODM\MongoDB\Mapping\Types\Type;
use Dough\Money\Money;

class DoughMoneyType extends Type
{
    public function convertToDatabaseValue($value)
    {
        return (string) $value->getAmount();
    }

    public function convertToPHPValue($value)
    {
        return new Money($value);
    }

    public function closureToMongo()
    {
        return '$return = (string) $value->getAmount();';
    }

    public function closureToPHP()
    {
        return '$return = new Money($value);';
    }
}
