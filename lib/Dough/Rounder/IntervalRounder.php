<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dough\Rounder;

/**
 * Used to round currency amounts to a given interval. Commonly referred to as
 * Swedish rounding.
 *
 * @author Tim Nagel <tim@nagel.com.au>
 * @see http://en.wikipedia.org/wiki/Swedish_rounding
 */
class IntervalRounder implements RounderInterface
{
    private $precision;

    public function __construct($interval, $precision)
    {
        $this->interval = intval($interval);
        $this->precision = intval($precision);
    }

    /**
     * Rounds a value using the PHP round() function.
     *
     * @param float $value
     * @param string|null $currency
     * @return float
     */
    public function round($value, $currency = null)
    {
        return round($value / $this->interval, $this->precision) * $this->interval;
    }

    /**
     * @return int
     */
    public function getPrecision()
    {
        return $this->precision;
    }

    /**
     * @return int
     */
    public function getInterval()
    {
        return $this->interval;
    }
}
