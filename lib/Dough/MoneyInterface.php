<?php

namespace Dough;

interface MoneyInterface
{
    /**
     * @param Bank $bank
     * @param $toCurrency
     * @return Money
     */
    function reduce(Bank $bank, $toCurrency);

    /**
     * @param MoneyInterface $addend
     * @return Sum
     */
    function plus(MoneyInterface $addend);

    /**
     * @param $multiplier
     * @return MoneyInterface
     */
    function times($multiplier);
}