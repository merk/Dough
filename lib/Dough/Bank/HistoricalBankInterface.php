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

use DateTime;

/**
 * Historical Bank interface. Supplies additional historical
 * based functions for currency exchange.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface HistoricalBankInterface extends BankInterface
{
    /**
     * Returns the current exchange rate between 2 currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param \DateTime $at
     *
     * @return float
     *
     * @throws InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function getRateAt($fromCurrency, $toCurrency, DateTime $at);

    /**
     * Reduces the supplied object to the specified currency.
     *
     * @param MoneyInterface $source
     * @param string $toCurrency
     * @param \DateTime $at
     *
     * @return MoneyInterface
     *
     * @throws InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function reduceAt(MoneyInterface $source, $toCurrency, DateTime $at);
}