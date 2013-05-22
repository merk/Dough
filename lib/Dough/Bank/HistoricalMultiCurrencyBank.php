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

use Dough\Exchanger\HistoricalExchangerInterface;
use Dough\Money\MultiCurrencyMoneyInterface;
use Dough\Rounder\RounderInterface;

/**
 * Handles the reduction of different monetary objects into a single
 * currency.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class HistoricalMultiCurrencyBank extends MultiCurrencyBank implements HistoricalBankInterface
{
    /**
     * @var \Dough\Exchanger\HistoricalExchangerInterface
     */
    protected $exchanger;

    public function __construct(
        array $currencies,
        $baseCurrency,
        HistoricalExchangerInterface $exchanger,
        $moneyClass = 'Dough\\Money\\MultiCurrencyMoney',
        RounderInterface $rounder = null
    ) {
        parent::__construct($currencies, $baseCurrency, $exchanger, $moneyClass, $rounder);
    }

    /**
     * Returns the current exchange rate between 2 currencies.
     *
     * @param \DateTime $at
     * @param string $fromCurrency
     * @param string $toCurrency
     *
     * @return float
     *
     * @throws \InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function getRateAt(\DateTime $at, $fromCurrency, $toCurrency = null)
    {
        if (null === $toCurrency) {
            $toCurrency = $this->getBaseCurrency();
        }

        $this->checkCurrencies(array($fromCurrency, $toCurrency));

        return $this->exchanger->getRateAt($fromCurrency, $toCurrency, $at);
    }

    /**
     * Reduces the supplied object to the specified currency.
     *
     * @param \DateTime $at
     * @param \Dough\Money\MultiCurrencyMoneyInterface $source
     * @param string $toCurrency
     *
     * @return MultiCurrencyMoneyInterface
     *
     * @throws \InvalidArgumentException when a currency does not exist
     *         or when the supplied currencies do not have an exchange
     *         rate set.
     */
    public function reduceAt(\DateTime $at, MultiCurrencyMoneyInterface $source, $toCurrency = null)
    {
        if (null === $toCurrency) {
            $toCurrency = $this->getBaseCurrency();
        }

        $this->checkCurrencies($toCurrency);

        $rate = $this->getRateAt($at, $source->getCurrency(), $toCurrency);

        return $source->reduce($this, $toCurrency, $rate);
    }
}
