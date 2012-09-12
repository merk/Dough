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
 * Represents a product of a monetary object.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Product extends BaseMoney
{
    private $multiplicand;
    private $multiplier;

    /**
     * Adds an multiplier to the multiplicand.
     *
     * @param MoneyInterface $multiplicand
     * @param float|int $multiplier
     */
    public function __construct(MoneyInterface $multiplicand, $multiplier)
    {
        $this->multiplicand = $multiplicand;
        $this->multiplier = floatval($multiplier);
    }

    /**
     * Returns the multiplicand of this product.
     *
     * @return MoneyInterface
     */
    public function getMultiplicand()
    {
        return $this->multiplicand;
    }

    /**
     * Returns to the multiplier.
     *
     * @return float|int
     */
    public function getMultiplier()
    {
        return $this->multiplier;
    }

    /**
     * Reduces the product to a single unit of currency.
     *
     * @param \Dough\Bank\BankInterface $bank
     * @return Money
     */
    public function reduce(BankInterface $bank = null)
    {
        if (null === $bank) {
            $bank = static::getBank();
        }

        $rounder = $bank->getRounder();
        $amount = bcmul($this->multiplicand->reduce($bank)->getAmount(),
                        $this->multiplier,
                        $rounder->getPrecision() + 1);
        $amount = $rounder->round($amount);

        return new Money($amount);
    }

    /**
     * Multiplies all items of this product by the multiplier.
     *
     * @param int|float $multiplier
     * @return Product
     */
    public function times($multiplier)
    {
        return new static($this->multiplicand, $this->multiplier);
    }
}
