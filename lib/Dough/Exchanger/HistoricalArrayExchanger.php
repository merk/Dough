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
class HistoricalArrayExchanger extends BaseArrayExchanger implements HistoricalExchangerInterface
{
    /**
     * Stores exchange rates in the format of {$fromCurrency}-{$toCurrency}
     * as the key with the value being the exchange rate.
     *
     * @var array
     */
    private $rates = array();

    /**
     * Add a new currency conversion rate at the specified date.
     *
     * @param string    $fromCurrency
     * @param string    $toCurrency
     * @param \DateTime $at
     * @param float     $rate
     */
    public function addRate($fromCurrency, $toCurrency, \DateTime $at, $rate)
    {
        $currencyKey = $this->getCurrencyKey($fromCurrency, $toCurrency);
        $date = $at->format('Y-m-d');

        $this->rates[$currencyKey][$date] = $rate;
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
        $today = new \DateTime;

        return $this->getRateAt($fromCurrency, $toCurrency, $today);
    }

    /**
     * Returns the currency conversion rate at the specified date.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param \DateTime $at
     *
     * @return float
     *
     * @throws \Dough\Exception\NoExchangeRateException when the exchanger doesnt
     *         have a rate for the specified currencies.
     */
    public function getRateAt($fromCurrency, $toCurrency, \DateTime $at)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        $currencyKey = $this->getCurrencyKey($fromCurrency, $toCurrency);
        $date = $at->format('Y-m-d');

        if (!isset($this->rates[$currencyKey]) or !isset($this->rates[$currencyKey][$date])) {
            throw new NoExchangeRateException(sprintf(
                'Cannot convert %s to %s at %s, no exchange rate found.',
                $fromCurrency,
                $toCurrency,
                $date
            ));
        }

        return $this->rates[$currencyKey][$date];
    }
}
