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
class Bank implements BankInterface
{
    private $rates = array();
    private $currencies = array();

    /**
     * Checks to see if the currencies supplied are known or
     * unknown.
     *
     * @param array $currencies
     * @throws \InvalidArgumentException when currencies are unknown.
     */
    protected function checkCurrencies(array $currencies)
    {
        foreach ($currencies AS $currency) {
            if (!$this->hasCurrency($currency)) {
                throw new \InvalidArgumentException(sprintf('"%s" is an unknown currency code.', $currency));
            }
        }
    }

    public function __construct(array $currencies, array $rates = null)
    {
        $this->currencies = $currencies;
    }

    /**
     * Checks if the Bank knows about a specified currency.
     *
     * @param string $currencyCode
     * @return bool
     */
    public function hasCurrency($currencyCode)
    {
        return isset($this->currencies[$currencyCode]);
    }

    /**
     * Add a new currency conversion rate.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $rate
     *
     * @throws \InvalidArgumentException when a supplied currency is
     *         unknown.
     */
    public function addRate($fromCurrency, $toCurrency, $rate)
    {
        $this->checkCurrencies(array($fromCurrency, $toCurrency));

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
     * @throws \InvalidArgumentException when a supplied currency is
     *         unknown.
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $this->checkCurrencies(array($fromCurrency, $toCurrency));

        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        $currencyString = "{$fromCurrency}-{$toCurrency}";
        if (!isset($this->rates[$currencyString])) {
            throw new \InvalidArgumentException(sprintf('Cannot convert %s to %s, no exchange rate found.', $fromCurrency, $toCurrency));
        }

        return $this->rates[$currencyString];
    }

    /**
     * Reduces the supplied monetary object into a single
     * Money object of the supplied currency.
     *
     * @param MoneyInterface $source
     * @param string $toCurrency
     *
     * @return Money
     *
     * @throws \InvalidArgumentException when a supplied currency is
     *         unknown.
     */
    public function reduce(MoneyInterface $source, $toCurrency)
    {
        $this->checkCurrencies(array($toCurrency));

        return $source->reduce($this, $toCurrency);
    }
}

