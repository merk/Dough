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
use Dough\Bank\MultiCurrencyBankInterface;

/**
 * Represents a unit of currency. This object is immutable,
 * most operations will return new objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class MultiCurrencyMoney extends Money implements MultiCurrencyMoneyInterface
{
    private $currency;

    /**
     * @param float $amount
     * @param string $currency
     */
    public function __construct($amount, $currency)
    {
        parent::__construct($amount);

        $this->currency = $currency;
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
        if (!$money instanceof MultiCurrencyMoney) {
            return false;
        }

        return $money->currency == $this->currency && $money->getAmount() == $this->getAmount();
    }

    /**
     * Adds an addend to this object.
     *
     * @param MoneyInterface $addend
     * @return Sum
     */
    public function plus(MoneyInterface $addend)
    {
        return new MultiCurrencySum($this, $addend);
    }

    /**
     * Multiplies this object by the multiplier and returns
     * a new object of that value.
     *
     * @param int|float $multiplier
     * @return MultiCurrencyMoneyInterface
     */
    public function times($multiplier)
    {
        return new MultiCurrencyProduct($this, $multiplier);
    }

    /**
     * Reduces the value of this object to the supplied currency.
     *
     * @param \Dough\Bank\BankInterface $bank
     * @param string $toCurrency
     * @param float $rate
     *
     * @return MultiCurrencyMoneyInterface
     *
     * @throws \InvalidArgumentException when the supplied $bank does not
     *         support currency conversion.
     */
    public function reduce(BankInterface $bank = null, $toCurrency = null, $rate = null)
    {
        if (null === $bank) {
            $bank = static::getBank();
        }

        if (!$bank instanceof MultiCurrencyBankInterface) {
            throw new \InvalidArgumentException('The supplied bank must implement MultiCurrencyBankInterface');
        }

        if (null === $rate) {
            $rate = $bank->getRate($this->currency, $toCurrency);
        }

        $rounder = $bank->getRounder();
        $amount = bcmul($this->getAmount(), $rate, $rounder->getPrecision() + 1);

        return $bank->createMoney($rounder->round($amount, $toCurrency), $toCurrency);
    }
}
