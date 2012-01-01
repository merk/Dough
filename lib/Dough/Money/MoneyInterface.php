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

use Dough\Bank\BankInterface;

/**
 * Represents an object of money that can will accept
 * certain operations.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface MoneyInterface
{
    /**
     * Reduces the object to a specified currency. Used
     *
     * @param \Dough\Bank\BankInterface $bank
     *
     * @return Money
     */
    public function reduce(BankInterface $bank = null);

    /**
     * Adds the addend to the money object.
     *
     * @param MoneyInterface $addend
     *
     * @return Sum
     */
    public function plus(MoneyInterface $addend);

    /**
     * Subtracts the subtrahend from the money object.
     *
     * @param MoneyInterface $subtrahend
     * @return Sum
     */
    public function subtract(MoneyInterface $subtrahend);

    /**
     * Multiplies the money object by the multiplier.
     *
     * @param int|float $multiplier
     * @return MoneyInterface
     */
    public function times($multiplier);

    /**
     * Divides the money object by the divisor.
     *
     * @param int|float $divisor
     * @return MoneyInterface
     */
    public function divide($divisor);
}