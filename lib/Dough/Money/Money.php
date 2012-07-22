<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Money;

use Dough\Bank\BankInterface;

/**
 * Represents a unit of currency. This object is immutable,
 * most operations will return new objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Money extends BaseMoney
{
    private $amount;

    /**
     * @param float $amount
     */
    public function __construct($amount)
    {
        $this->amount = floatval($amount);
    }

    /**
     * Returns the amount of currency represented by this
     * object.
     *
     * @return float
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Tests if two objects are of equal value.
     *
     * @param Money $money
     *
     * @return bool
     */
    public function equals(Money $money)
    {
        return $money->amount == $this->amount;
    }

    /**
     * Reduces the value of this object to a single object.
     *
     * @param \Dough\Bank\BankInterface $bank
     *
     * @return Money
     */
    public function reduce(BankInterface $bank = null)
    {
        return clone $this;
    }
}
