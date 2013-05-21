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

interface RounderInterface
{
    /**
     * Rounds the given value according to specific rounding
     * strategies.
     *
     * @param float $value
     * @param string|null $currency
     * @return float
     */
    public function round($value, $currency = null);

    /**
     * Returns the precision of the rounder. The underlying
     * classes that use the rounder will use this precision
     * with bc math function to make sure that the operation
     * has enough precision.
     *
     * @return integer
     */
    public function getPrecision();
}
