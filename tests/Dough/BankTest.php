<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Bank;
use Dough\Money;
use Dough\Sum;

class BankText extends PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testAddInvalidRate()
    {
        $bank = new Bank(array('USD', 'CHF'));
        $bank->addRate('XTS', 'USD', 5);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testNoRate()
    {
        $bank = new Bank(array('USD', 'CHF'));
        $bank->getRate('USD', 'CHF');
    }
}