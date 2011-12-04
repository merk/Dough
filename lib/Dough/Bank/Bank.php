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

use Dough\Exception\InvalidCurrencyException;
use Dough\Exception\NoExchangeRateException;
use Dough\Money\Money;
use Dough\Money\MoneyInterface;

/**
 * Handles the reduction of different monetary objects into a single
 * currency.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Bank implements BankInterface
{
    /**
     * Stores exchange rates in the format of {$fromCurrency}-{$toCurrency}
     * as the key with the value being the exchange rate.
     *
     * @var array
     */
    private $rates = array();

    /**
     * Stores an array of known currency codes.
     *
     * @var array
     */
    private $currencies = array();

    /**
     * Stores the base currency to be used by this bank.
     *
     * @var string
     */
    private $baseCurrency;

    /**
     * Constructor.
     *
     * @param array $currencies An array of currencies this bank knows about.
     * @param string $baseCurrency The base currency to be used by the bank.
     *
     * @throws \Dough\Exception\InvalidCurrencyException when the base currency
     *         is unknown.
     */
    public function __construct(array $currencies, $baseCurrency)
    {
        $this->currencies = $currencies;
        $this->setBaseCurrency($baseCurrency);
    }

    /**
     * Checks to see if the currencies supplied are known or
     * unknown.
     *
     * @param array|string $currencies
     * @throws \Dough\Exception\InvalidCurrencyException when currencies are unknown.
     */
    protected function checkCurrencies($currencies)
    {
        foreach ((array) $currencies as $currency) {
            if (!$this->hasCurrency($currency)) {
                throw new InvalidCurrencyException(sprintf('"%s" is an unknown currency code.', $currency));
            }
        }
    }

    /**
     * Checks if the Bank knows about a specified currency.
     *
     * @param string $currencyCode
     * @return bool
     */
    public function hasCurrency($currencyCode)
    {
        return false !== array_search((string) $currencyCode, $this->currencies);
    }

    /**
     * Sets the base currency for calculations.
     *
     * @param string $baseCurrency
     *
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied currency is
     *         unknown.
     */
    public function setBaseCurrency($baseCurrency)
    {
        $this->checkCurrencies($baseCurrency);

        $this->baseCurrency = $baseCurrency;
    }

    /**
     * Returns the base currency code to be used by the bank.
     *
     * @return string
     */
    public function getBaseCurrency()
    {
        return $this->baseCurrency;
    }

    /**
     * Add a new currency conversion rate.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param float $rate
     *
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied currency is
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
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied currency is
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
            throw new NoExchangeRateException(sprintf('Cannot convert %s to %s, no exchange rate found.', $fromCurrency, $toCurrency));
        }

        return $this->rates[$currencyString];
    }

    /**
     * Reduces the supplied monetary object into a single
     * Money object of the supplied currency.
     *
     * @param \Dough\Money\MoneyInterface $source
     * @param string $toCurrency
     *
     * @return Money
     *
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied currency is
     *         unknown.
     */
    public function reduce(MoneyInterface $source, $toCurrency)
    {
        $this->checkCurrencies(array($toCurrency));

        return $source->reduce($this, $toCurrency);
    }

    /**
     * Creates a new money instance. If currency is not supplied
     * the base currency for this bank is used.
     *
     * @param float|int $amount
     * @param string|null $currency
     * @return \Dough\Money\Money
     */
    public function createMoney($amount, $currency = null)
    {
        if (null === $currency) {
            $currency = $this->getBaseCurrency();
        }

        $this->checkCurrencies($currency);

        return new Money($amount, $currency);
    }
}