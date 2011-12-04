<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Money;

/**
 * An abstract money class that implements common
 * features used in all money objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
abstract class BaseMoney implements MoneyInterface
{
    /**
     * Subtracts the subtrahend from the money object.
     *
     * The subtrahend should be passed in as a positive value.
     *
     * @param MoneyInterface $subtrahend
     * @return Sum
     */
    public function subtract(MoneyInterface $subtrahend)
    {
        return $this->plus($subtrahend->times(-1));
    }

    /**
     * Divides the money object by the divisor.
     *
     * @param int|float $divisor
     * @return MoneyInterface
     */
    public function divide($divisor)
    {
        return $this->times(1 / $divisor);
    }
}