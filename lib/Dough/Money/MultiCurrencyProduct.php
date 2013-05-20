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
use Dough\Bank\MultiCurrencyBankInterface;

/**
 * Represents a multi currency product of a monetary object.
 *
 * @author Denis Vasilev <yethee@biplane.ru>
 */
class MultiCurrencyProduct extends Product implements MultiCurrencyMoneyInterface
{
    /**
     * Adds an addend to this sum.
     *
     * @param MoneyInterface $addend
     *
     * @return MultiCurrencySum
     */
    public function plus(MoneyInterface $addend)
    {
        return new MultiCurrencySum($this, $addend);
    }

    /**
     * Reduces the product to a single unit of currency.
     *
     * @param BankInterface $bank       The bank
     * @param string|null   $toCurrency The currency code
     *
     * @return MultiCurrencyMoneyInterface
     */
    public function reduce(BankInterface $bank = null, $toCurrency = null)
    {
        if (null === $bank) {
            $bank = static::getBank();
        }

        if (!$bank instanceof MultiCurrencyBankInterface) {
            throw new \InvalidArgumentException('The supplied bank must implement MultiCurrencyBankInterface');
        }

        $rounder = $bank->getRounder();
        $amount = bcmul(
            $this->getMultiplicand()->reduce($bank, $toCurrency)->getAmount(),
            $this->getMultiplier(),
            $rounder->getPrecision() + 1
        );

        $amount = $rounder->round($amount, $toCurrency);

        return $bank->createMoney($amount, $toCurrency);
    }
}
