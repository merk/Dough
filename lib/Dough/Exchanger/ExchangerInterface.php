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
 * An interface that describes the method used to retrieve a current
 * exchange rate.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface ExchangerInterface
{
    /**
     * Returns the currency conversion rate for the supplied
     * currencies.
     *
     * @param string $fromCurrency
     * @param string $toCurrency
     *
     * @return float
     */
    public function getRate($fromCurrency, $toCurrency);
}