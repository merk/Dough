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
 * Represents an object of money that can will accept
 * certain operations.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface MoneyInterface
{
    /**
     * @param Bank $bank
     * @param string $toCurrency
     * @return Money
     */
    function reduce(Bank $bank, $toCurrency);

    /**
     * @param MoneyInterface $addend
     * @return Sum
     */
    function plus(MoneyInterface $addend);

    /**
     * @param int|float $multiplier
     * @return MoneyInterface
     */
    function times($multiplier);
}