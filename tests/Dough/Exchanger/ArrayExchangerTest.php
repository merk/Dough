<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Exchanger\ArrayExchanger;

class ArrayExchangerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Exchanger\ArrayExchanger
     */
    protected $exchanger;

    protected function setUp()
    {
        $this->exchanger = new ArrayExchanger();
    }

    public function testAddRate()
    {
        $this->exchanger->addRate('USD', 'CHF', 0.5);
    }

    public function testGetRate()
    {
        $this->exchanger->addRate('USD', 'CHF', 0.5);

        $this->assertEquals(0.5, $this->exchanger->getRate('USD', 'CHF'));
    }
}
