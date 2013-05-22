<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Exchanger;

use Dough\Exception\NoExchangeRateException;

/**
 * A base array exchanger providing functions used by both
 * ArrayExchanger and HistoricalArrayExchanger.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class BaseArrayExchanger
{
    /**
     * Returns a string that can be used as an array key for storing
     * currency exchange rates.
     *
     * @param  string $fromCurrency
     * @param  string $toCurrency
     *
     * @return string
     */
    protected function getCurrencyKey($fromCurrency, $toCurrency)
    {
        return sprintf('%s-%s', $fromCurrency, $toCurrency);
    }
}
