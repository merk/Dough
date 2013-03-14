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

use Dough\Bank\Bank;
use Dough\Bank\BankInterface;

/**
 * An abstract money class that implements common
 * features used in all money objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class BaseMoney implements MoneyInterface
{
    protected static $bank;

    /**
     * Format used by __toString
     *
     * @var string
     */
    public static $format = '$ %01.2f';

    /**
     * Returns the static bank instance.
     *
     * @static
     * @return BankInterface
     */
    protected static function getBank()
    {
        if (null === static::$bank) {
            static::$bank = new Bank();
        }

        return static::$bank;
    }

    /**
     * @static
     * @param \Dough\Bank\BankInterface $bank
     */
    public static function setBank(BankInterface $bank)
    {
        static::$bank = $bank;
    }

    /**
     * Adds an addend to this sum.
     *
     * @param MoneyInterface $addend
     * @return Sum
     */
    public function plus(MoneyInterface $addend)
    {
        return new Sum($this, $addend);
    }

    /**
     * Subtracts the subtrahend from the money object.
     *
     * The subtrahend should be passed in as a positive value.
     *
     * @param MoneyInterface $subtrahend
     * @return Sum
     */
    public function subtract(MoneyInterface $subtrahend)
    {
        return $this->plus($subtrahend->times(-1));
    }

    /**
     * Multiplies this object by the multiplier and returns
     * a new object of that value.
     *
     * @param int|float $multiplier
     * @return Money
     */
    public function times($multiplier)
    {
        return new Product($this, $multiplier);
    }

    /**
     * Divides the money object by the divisor.
     *
     * @param int|float $divisor
     * @return MoneyInterface
     */
    public function divide($divisor)
    {
        return $this->times(1 / $divisor);
    }

    /**
     * Lets you echo a money
     */
    public function __toString()
    {
        return sprintf(static::$format, $this->reduce()->getAmount());
    }
}
