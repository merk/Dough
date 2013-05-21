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

use Dough\Exception\InvalidCurrencyException;
use Dough\Exception\NoExchangeRateException;
use Dough\Money\Money;
use Dough\Money\MoneyInterface;
use Dough\Rounder\BasicRounder;
use Dough\Rounder\RounderInterface;

/**
 * Handles the reduction of different monetary objects.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 */
class Bank implements BankInterface
{
    /**
     * Class of the Money object to create.
     *
     * @var string
     */
    protected $moneyClass;

    /**
     * The rounder to be used when rounding values.
     *
     * @var RounderInterface
     */
    protected $rounder;

    /**
     * Constructor.
     *
     * @param string $moneyClass FQCN of the Money class to use.
     * @param RounderInterface $rounder
     */
    public function __construct($moneyClass = 'Dough\\Money\\Money', RounderInterface $rounder = null)
    {
        $this->moneyClass = $moneyClass;

        if (null === $rounder) {
            $rounder = new BasicRounder(2, PHP_ROUND_HALF_UP);
        }
        $this->rounder = $rounder;
    }

    /**
     * Reduces the supplied monetary object into a single
     * Money object.
     *
     * @param \Dough\Money\MoneyInterface $source
     *
     * @return \Dough\Money\Money
     */
    public function reduce(MoneyInterface $source)
    {
        return $source->reduce($this);
    }

    /**
     * Creates a new money instance. If currency is not supplied
     * the base currency for this bank is used.
     *
     * @param float|int $amount
     *
     * @return \Dough\Money\Money
     */
    public function createMoney($amount)
    {
        $class = $this->moneyClass;

        return new $class($amount);
    }

    /**
     * Returns the rounder to use for rounding operations.
     *
     * @return \Dough\Rounder\RounderInterface
     */
    public function getRounder()
    {
        return $this->rounder;
    }
}
