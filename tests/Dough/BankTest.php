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
        $this->bank = new Bank(array('USD', 'CHF'), 'USD');
    }

    /**
     * @expectedException \Dough\Exception\InvalidCurrencyException
     */
    public function testAddInvalidRate()
    {
        $this->bank->addRate('XTS', 'USD', 5);
    }

    /**
     * @expectedException \Dough\Exception\InvalidCurrencyException
     */
    public function testInvalidBaseCurrency()
    {
        new Bank(array(), 'USD');
    }

    /**
     * @expectedException \Dough\Exception\NoExchangeRateException
     */
    public function testNoRate()
    {
        $this->bank->getRate('USD', 'CHF');
    }

    public function testCreateBaseCurrencyMoney()
    {
        $money = $this->bank->createMoney(10);

        $this->assertInstanceOf('Dough\Money\Money', $money);
        $this->assertEquals('USD', $money->getCurrency());
    }

    public function testCreateMoney()
    {
        $money = $this->bank->createMoney(10, 'CHF');

        $this->assertEquals('CHF', $money->getCurrency());
    }

    /**
     * @expectedException \Dough\Exception\InvalidCurrencyException
     */
    public function testCreateMoneyUnknownCurrency()
    {
        $this->bank->createMoney(10, 'XSF');
    }
}