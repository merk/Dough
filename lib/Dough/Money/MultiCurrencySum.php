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
 * Represents a multi currency sum of multiple monetary objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class MultiCurrencySum extends Sum implements MultiCurrencyMoneyInterface
{
    /**
     * Multiplies all items of this sum by the multiplier.
     *
     * @param int|float $multiplier
     * @return MultiCurrencyMoneyInterface
     */
    public function times($multiplier)
    {
        return new self($this->getAugend()->times($multiplier), $this->getAddend()->times($multiplier));
    }

    /**
     * Adds an addend to this sum.
     *
     * @param MoneyInterface $addend
     * @return MultiCurrencyMoneyInterface
     */
    public function plus(MoneyInterface $addend)
    {
        return new MultiCurrencySum($this, $addend);
    }

    /**
     * Reduces the sum to a single unit of currency.
     *
     * @param \Dough\Bank\BankInterface $bank
     * @param string|null $toCurrency
     *
     * @return MultiCurrencyMoneyInterface
     *
     * @throws \InvalidArgumentException when the supplied $bank does not
     *         support currency conversion.
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
        $amount = bcadd(
            $this->getAugend()->reduce($bank, $toCurrency)->getAmount(),
            $this->getAddend()->reduce($bank, $toCurrency)->getAmount(),
            $rounder->getPrecision() + 1
        );

        $amount = $rounder->round($amount, $toCurrency);

        return $bank->createMoney($amount, $toCurrency);
    }
}
