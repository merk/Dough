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
 * A currency exchanger that uses a basic PHP array to store exchange rates.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class ArrayExchanger implements ExchangerInterface
{
    /**
     * Stores exchange rates in the format of {$fromCurrency}-{$toCurrency}
     * as the key with the value being the exchange rate.
     *
     * @var array
     */
    private $rates = array();

    /**
     * Add a new currency conversion rate.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $rate
     */
    public function addRate($fromCurrency, $toCurrency, $rate)
    {
        $this->rates["{$fromCurrency}-{$toCurrency}"] = $rate;
    }

    /**
     * Returns the conversion rate between two currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     *
     * @return float
     *
     * @throws \Dough\Exception\NoExchangeRateException when the exchanger doesnt
     *         have a rate for the specified currencies.
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        $currencyString = "{$fromCurrency}-{$toCurrency}";
        if (!isset($this->rates[$currencyString])) {
            throw new NoExchangeRateException(sprintf(
                'Cannot convert %s to %s, no exchange rate found.',
                $fromCurrency,
                $toCurrency
            ));
        }

        return $this->rates[$currencyString];
    }
}
