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

class BasicRounder implements RounderInterface
{
    private $precision;
    private $mode;

    public function __construct($precision, $mode)
    {
        $this->precision = floatval($precision);
        $this->mode = $mode;
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
        return round($value, $this->precision, $this->mode);
    }
}
