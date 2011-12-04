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
 * A bank that can handle currency conversion operations.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
interface MultiCurrencyMoneyInterface extends MoneyInterface
{
    /**
     * Reduces the object to a specified currency. Used
     *
     * @param \Dough\Bank\BankInterface $bank
     * @param string $toCurrency
     * @return Money
     */
    // public function reduce(BankInterface $bank, $toCurrency = null);
}