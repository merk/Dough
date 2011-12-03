<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough;

/**
 * Bank interface
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface BankInterface
{
    /**
     * Checks if the bank can handle a specified currency.
     *
     * @param $currencyCode
     * @return bool
     */
    public function hasCurrency($currencyCode);

    /**
     * Returns the current exchange rate between 2 currencies.
     *
     * @param $fromCurrency
     * @param $toCurrency
     *
     * @return float
     *
     * @throws InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function getRate($fromCurrency, $toCurrency);

    /**
     * Reduces the supplied object to the specified currency.
     *
     * @param MoneyInterface $source
     * @param $toCurrency
     *
     * @return MoneyInterface
     *
     * @throws InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function reduce(MoneyInterface $source, $toCurrency);
}