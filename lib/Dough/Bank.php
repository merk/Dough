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
 * Handles the reduction of different monetary objects into a single
 * currency.
 */
class Bank
{
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
     * @return float
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        return $this->rates["{$fromCurrency}-{$toCurrency}"];
    }

    /**
     * Reduces the supplied monetary object into a single
     * Money object of the supplied currency.
     *
     * @param MoneyInterface $source
     * @param string $toCurrency
     * @return Money
     */
    public function reduce(MoneyInterface $source, $toCurrency)
    {
        return $source->reduce($this, $toCurrency);
    }
}

