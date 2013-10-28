<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

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
        return '$return = new \Dough\Money\Money($value);';
    }
}
