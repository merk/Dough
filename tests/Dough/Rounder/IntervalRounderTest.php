<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Money\Money;
use Dough\Rounder\IntervalRounder;

class IntervalRounderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider get5Data
     */
    public function test5Interval($input, $output)
    {
        $rounder = new IntervalRounder(5, 2);

        $this->assertEquals($output, $rounder->round($input));
    }

    public function get5Data()
    {
        return array(
            array(1.11, 1.10),
            array(1.07, 1.05),
            array(10.03, 10.05),
            array(95.42, 95.40),
            array(9999.99, 10000)
        );
    }

    /**
     * @dataProvider get10Data
     */
    public function test10Interval($input, $output)
    {
        $rounder = new IntervalRounder(10, 2);

        $this->assertEquals($output, $rounder->round($input));
    }

    public function get10Data()
    {
        return array(
            array(1.11, 1.10),
            array(1.07, 1.10),
            array(10.03, 10.00),
            array(95.42, 95.40),
            array(9999.99, 10000)
        );
    }
}
