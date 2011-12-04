<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Bank\Bank;
use Dough\Money\Money;

class BankText extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Bank\Bank
     */
    protected $bank;

    protected function setUp()
    {
        $this->bank = new Bank('Dough\\Money\\Money');
    }

    public function testCreateMoney()
    {
        $money = $this->bank->createMoney(10);

        $this->assertInstanceOf('Dough\\Money\\MoneyInterface', $money);
        $this->assertEquals(10, $money->getAmount());
    }
}