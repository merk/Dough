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

/**
 * A historical exchanger that can additionally retrieve
 * exchange rates from the past.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface HistoricalExchangerInterface extends ExchangerInterface
{
    /**
     * Returns the currency conversion rate at the specified
     * time.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     * @param \DateTime $at
     *
     * @return float
     */
    public function getRateAt($fromCurrency, $toCurrency, \DateTime $at);
}