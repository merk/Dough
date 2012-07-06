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

use Dough\Money\MoneyInterface;

/**
 * Bank interface
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface BankInterface
{
    /**
     * Reduces the supplied object to a single Money
     * object.
     *
     * @param \Dough\Money\MoneyInterface $source
     * @return MoneyInterface
     */
    public function reduce(MoneyInterface $source);

    /**
     * Creates a new money instance.
     *
     * @param float $amount
     * @return MoneyInterface
     */
    public function createMoney($amount);

    /**
     * Returns the rounder to be used for rounding operations.
     *
     * @return \Dough\Rounder\RounderInterface
     */
    public function getRounder();
}
