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
 * Represents a sum of multiple monetary objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Sum implements MoneyInterface
{
    private $augend;
    private $addend;

    /**
     * Adds an addend to the augend.
     *
     * @param MoneyInterface $augend
     * @param MoneyInterface $addend
     */
    public function __construct(MoneyInterface $augend, MoneyInterface $addend)
    {
        $this->augend = $augend;
        $this->addend = $addend;
    }

    /**
     * Returns the augend of this sum.
     *
     * @return MoneyInterface
     */
    public function getAugend()
    {
        return $this->augend;
    }

    /**
     * Returns to the addend.
     *
     * @return MoneyInterface
     */
    public function getAddend()
    {
        return $this->addend;
    }

    /**
     * Reduces the sum to a single unit of currency.
     *
     * @param \Dough\Bank\BankInterface $bank
     * @param string $toCurrency
     * @return Money
     */
    public function reduce(BankInterface $bank, $toCurrency)
    {
        $amount = $this->augend->reduce($bank, $toCurrency)->getAmount() +
                  $this->addend->reduce($bank, $toCurrency)->getAmount();

        return new Money($amount, $toCurrency);
    }

    /**
     * Adds an addend to this sum.
     *
     * @param MoneyInterface $addend
     * @return Sum
     */
    public function plus(MoneyInterface $addend)
    {
        return new Sum($this, $addend);
    }

    /**
     * Subtracts the subtrahend from the money object.
     *
     * @param MoneyInterface $subtrahend
     * @return Sum
     */
    public function subtract(MoneyInterface $subtrahend)
    {
        return $this->plus($subtrahend->times(-1));
    }

    /**
     * Multiplies all items of this sum by the multiplier.
     *
     * @param int|float $multiplier
     * @return Sum
     */
    public function times($multiplier)
    {
        return new Sum($this->augend->times($multiplier), $this->addend->times($multiplier));
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
