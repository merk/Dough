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
use Dough\Money\MultiCurrencyMoneyInterface;
use Dough\Money\MultiCurrencyMoney;
use Dough\Money\MultiCurrencySum;

class MultiCurrencyMoneyText extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Bank\MultiCurrencyBank
     */
    protected $bank;

    protected function setUp()
    {
        $exchanger = new ArrayExchanger();
        $exchanger->addRate('CHF', 'USD', 0.5);
        $this->bank = new MultiCurrencyBank(array('USD', 'CHF'), 'USD', $exchanger);
    }

    public function testEquality()
    {
        $five = $this->bank->createMoney(5, 'USD');
        $six = $this->bank->createMoney(6, 'USD');
        $fiveFrancs = $this->bank->createMoney(5, 'CHF');

        $this->assertTrue($five->equals($five));
        $this->assertFalse($five->equals($six));
        $this->assertFalse($five->equals($fiveFrancs));
    }

    public function testCurrencyEquality()
    {
        $five = $this->bank->createMoney(5, 'USD');
        $noCurrencyFive = new Money(5);

        $this->assertFalse($five->equals($noCurrencyFive));
    }

    public function testDollarMultiplication()
    {
        $five = $this->bank->createMoney(5, 'USD');

        $this->assertEquals($this->bank->createMoney(10, 'USD'), $five->times(2)->reduce($this->bank, 'USD'));
        $this->assertEquals($this->bank->createMoney(15, 'USD'), $five->times(3)->reduce($this->bank, 'USD'));
    }

    public function testDivide()
    {
        $ten = $this->bank->createMoney(10, 'USD');

        $this->assertEquals($this->bank->createMoney(5, 'USD'), $ten->divide(2)->reduce($this->bank, 'USD'));
        $this->assertEquals($this->bank->createMoney(20, 'USD'), $ten->divide(.5)->reduce($this->bank, 'USD'));
    }

    public function testOddDivision()
    {
        $ten = $this->bank->createMoney(10, 'USD');

        $this->assertEquals($this->bank->createMoney(3.33, 'USD'), $ten->divide(3)->reduce($this->bank, 'USD'));
    }

    public function testCurrency()
    {
        $dollar = $this->bank->createMoney(1, 'USD');
        $franc = $this->bank->createMoney(1, 'CHF');

        $this->assertEquals('USD', $dollar->getCurrency());
        $this->assertEquals('CHF', $franc->getCurrency());
    }

    public function testReduceMoney()
    {
        $five = $this->bank->createMoney(5, 'USD');
        $result = $this->bank->reduce($five, 'USD');

        $this->assertTrue($five->equals($result));
    }

    public function testReduceDifferentCurrencies()
    {
        $result = $this->bank->reduce($this->bank->createMoney(2, 'CHF'), 'USD');

        $this->assertTrue($result->equals($this->bank->createMoney(1, 'USD')));
    }

    public function testMixedAddition()
    {
        $fiveDollars = $this->bank->createMoney(5, 'USD');
        $tenFrancs = $this->bank->createMoney(10, 'CHF');

        $result = $this->bank->reduce($fiveDollars->plus($tenFrancs), 'USD');
        $this->assertTrue($result->equals($this->bank->createMoney(10, 'USD')));
    }

    public function testSumPlusMoney()
    {
        $fiveDollars = $this->bank->createMoney(5, 'USD');
        $tenFrancs = $this->bank->createMoney(10, 'CHF');

        $sum = $fiveDollars->plus($tenFrancs)->plus($fiveDollars);
        $result = $this->bank->reduce($sum);

        $this->assertTrue($result->equals($this->bank->createMoney(15, 'USD')));
    }

    public function testSumMultiply()
    {
        $fiveDollars = $this->bank->createMoney(5, 'USD');
        $tenFrancs = $this->bank->createMoney(10, 'CHF');

        $sum = $fiveDollars->plus($tenFrancs)->times(2);
        $result = $this->bank->reduce($sum, 'USD');

        $this->assertTrue($result->equals($this->bank->createMoney(20, 'USD')));
    }
}