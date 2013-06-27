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

use Doctrine\ODM\MongoDB\Types\Type;
use Dough\Money\MultiCurrencyMoney;

class DoughCurrencyMoneyType extends Type
{
    public function convertToDatabaseValue($value)
    {
        return $value->getCurrency() . ':' . (string) $value->getAmount();
    }

    public function convertToPHPValue($value)
    {
        $money = explode(':', $value);
        return new MultiCurrencyMoney($money[1], $money[0]);
    }

    public function closureToMongo()
    {
        return '$return = $value->getCurrency() . \':\' . (string) $value->getAmount();';
    }

    public function closureToPHP()
    {
        return '$money = explode(\':\', $value);$return = new \Dough\Money\MultiCurrencyMoney($money[1], $money[0]);';
    }
}
