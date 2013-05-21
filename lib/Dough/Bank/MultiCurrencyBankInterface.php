<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Bank;

use Dough\Money\MoneyInterface;

/**
 * Bank interface
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface MultiCurrencyBankInterface extends BankInterface
{
    /**
     * Sets the base currency to be used when a currency is
     * not specified for an operation.
     *
     * @param string $baseCurrency
     */
    public function setBaseCurrency($baseCurrency);

    /**
     * Checks if the bank can handle a specified currency.
     *
     * @param string $currencyCode
     * @return bool
     */
    public function hasCurrency($currencyCode);

    /**
     * Returns the current exchange rate between 2 currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     *
     * @return float
     *
     * @throws \InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function getRate($fromCurrency, $toCurrency);

    /**
     * Reduces the supplied object to the specified currency.
     *
     * If no currency is supplied, it will convert to the default
     * currency.
     *
     * @param \Dough\Money\MultiCurrencyMoneyInterface $source
     * @param string|null $toCurrency
     *
     * @return \Dough\Money\MultiCurrencyMoneyInterface
     *
     * @throws \InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function reduce(MoneyInterface $source/*, $toCurrency = null*/);

    /**
     * Creates a new money instance.
     *
     * @param float $amount
     * @param string|null $currency
     *
     * @return \Dough\Money\MultiCurrencyMoneyInterface
     */
    public function createMoney($amount/*, $currency = null*/);
}
