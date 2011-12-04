<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Bank\MultiCurrencyBank;
use Dough\Exchanger\ArrayExchanger;
use Dough\Money\Money;

class MultiCurrencyBankText extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Bank\MultiCurrencyBank
     */
    protected $bank;

    /**
     * @var \Dough\Exchanger\ArrayExchanger
     */
    protected $exchanger;

    protected function setUp()
    {
        $this->exchanger = new ArrayExchanger();
        $this->bank = new MultiCurrencyBank('Dough\\Money\\MultiCurrencyMoney', array('USD', 'CHF'), 'USD', $this->exchanger);
    }

    /**
     * @expectedException \Dough\Exception\InvalidCurrencyException
     */
    public function testInvalidBaseCurrency()
    {
        new MultiCurrencyBank('Dough\\Money\\MultiCurrencyMoney', array(), 'USD', $this->exchanger);
    }

    /**
     * @expectedException \Dough\Exception\NoExchangeRateException
     */
    public function testNoRate()
    {
        $this->bank->getRate('USD', 'CHF');
    }

    public function testGetRates()
    {
        $this->assertEquals(1, $this->bank->getRate('USD', 'USD'));

        $this->exchanger->addRate('CHF', 'USD', 0.5);
        $this->assertEquals(0.5, $this->bank->getRate('CHF', 'USD'));
    }

    public function testCreateBaseCurrencyMoney()
    {
        $money = $this->bank->createMoney(10);

        $this->assertInstanceOf('Dough\\Money\\MultiCurrencyMoney', $money);
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