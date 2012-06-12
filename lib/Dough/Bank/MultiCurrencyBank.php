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
use Dough\Exchanger\ExchangerInterface;
use Dough\Money\Money;
use Dough\Money\MoneyInterface;
use Dough\Rounder\BasicRounder;
use Dough\Rounder\RounderInterface;

/**
 * Handles the reduction of different monetary objects into a single
 * currency.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class MultiCurrencyBank implements MultiCurrencyBankInterface
{
    /**
     * FQCN of the class to use when creating new money instances.
     *
     * @var string
     */
    protected $moneyClass;

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
     * The currency exchanger.
     *
     * @var \Dough\Exchanger\ExchangerInterface
     */
    private $exchanger;

    /**
     * The rounder to use for rounding.
     *
     * @var \Dough\Rounder\RounderInterface
     */
    private $rounder;

    /**
     * Constructor.
     *
     * @param string $moneyClass
     * @param array $currencies An array of currencies this bank knows about.
     * @param string $baseCurrency The base currency to be used by the bank.
     * @param \Dough\Exchanger\ExchangerInterface $exchanger
     * @param \Dough\Rounder\RounderInterface $rounder
     *
     * @throws \Dough\Exception\InvalidCurrencyException when the base currency
     *         is unknown.
     */
    public function __construct(array $currencies, $baseCurrency, ExchangerInterface $exchanger, $moneyClass = 'Dough\\Money\\MultiCurrencyMoney', RounderInterface $rounder = null)
    {
        $this->currencies = $currencies;
        $this->exchanger = $exchanger;
        $this->moneyClass = $moneyClass;

        $this->setBaseCurrency($baseCurrency);

        if (null === $rounder) {
            $rounder = new BasicRounder(2, PHP_ROUND_HALF_UP);
        }
        $this->rounder = $rounder;
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
     * Sets the base currency to be used when a currency is
     * not specified for an operation.
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
     * Returns the current exchange rate between 2 currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     *
     * @return float
     *
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied
     *         currency is unknown.
     * @throws \InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function getRate($fromCurrency, $toCurrency)
    {
        $this->checkCurrencies(array($fromCurrency, $toCurrency));

        return $this->exchanger->getRate($fromCurrency, $toCurrency);
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
    public function reduce(MoneyInterface $source, $toCurrency = null)
    {
        if (null === $toCurrency) {
            $toCurrency = $this->getBaseCurrency();
        }

        $this->checkCurrencies($toCurrency);

        return $source->reduce($this, $toCurrency);
    }

    /**
     * Creates a new money instance. If currency is not supplied
     * the base currency for this bank is used.
     *
     * @param float|int $amount
     * @param string|null $currency
     *
     * @return \Dough\Money\MultiCurrencyMoney
     *
     * @throws \Dough\Exception\InvalidCurrencyException when a supplied currency is
     *         unknown.
     */
    public function createMoney($amount, $currency = null)
    {
        if (null === $currency) {
            $currency = $this->getBaseCurrency();
        }

        $this->checkCurrencies($currency);

        $class = $this->moneyClass;
        return new $class($amount, $currency);
    }

    /**
     * Returns the rounder to be used for rounding operations.
     *
     * @return \Dough\Rounder\RounderInterface
     */
    public function getRounder()
    {
        return $this->rounder;
    }
}
