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
    private $currency;

    /**
     * @param float $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
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
     * Returns the currency represented by this object.
     *
     * @return string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Tests if two objects are of equal value.
     *
     * TODO: optionally supply a bank object to do a
     * currency conversion for an equals check?
     *
     * @param Money $money
     * @return bool
     */
    public function equals(Money $money)
    {
        return $money->currency == $this->currency && $money->amount == $this->amount;
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
        return new Money($this->amount * $multiplier, $this->currency);
    }

    /**
     * Adds the supplied monetary object to this object
     * and returns them as a new Sum.
     *
     * @param MoneyInterface $addend
     * @return Sum
     */
    public function plus(MoneyInterface $addend)
    {
        return new Sum($this, $addend);
    }

    /**
     * Reduces the value of this object to the supplied currency.
     *
     * @param \Dough\Bank\BankInterface $bank
     * @param string $toCurrency
     * @return Money
     */
    public function reduce(BankInterface $bank, $toCurrency)
    {
        $rate = $bank->getRate($this->currency, $toCurrency);
        return new Money((float) $this->amount * $rate, $toCurrency);
    }
}