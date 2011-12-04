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
use Dough\Money\Sum;

class MoneyText extends PHPUnit_Framework_TestCase
{
    protected function getBank()
    {
        $bank = new Bank(array('USD', 'CHF'), 'USD');
        $bank->addRate('CHF', 'USD', 0.5);

        return $bank;
    }

    public function testEquality()
    {
        $fiveDollars = new Money(5, 'USD');
        $sixDollars = new Money(6, 'USD');
        $fiveFrancs = new Money(5, 'CHF');

        $this->assertTrue($fiveDollars->equals($fiveDollars));
        $this->assertFalse($fiveDollars->equals($sixDollars));
        $this->assertFalse($fiveDollars->equals($fiveFrancs));
    }

    public function testDollarMultiplication()
    {
        $five = new Money(5, 'USD');

        $this->assertEquals(new Money(10, 'USD'), $five->times(2));
        $this->assertEquals(new Money(15, 'USD'), $five->times(3));
    }

    public function testFrancMultiplication()
    {
        $five = new Money(5, 'CHF');

        $this->assertEquals(new Money(10, 'CHF'), $five->times(2));
        $this->assertEquals(new Money(15, 'CHF'), $five->times(3));
    }

    public function testDivide()
    {
        $ten = new Money(10, 'USD');

        $this->assertEquals(new Money(5, 'USD'), $ten->divide(2));
        $this->assertEquals(new Money(20, 'USD'), $ten->divide(.5));
    }

    public function testOddDivision()
    {
        $ten = new Money(10, 'USD');

        $this->assertEquals(new Money(3.3333333333333, 'USD'), $ten->divide(3));
    }

    public function testCurrency()
    {
        $dollar = new Money(1, 'USD');
        $franc = new Money(1, 'CHF');

        $this->assertEquals('USD', $dollar->getCurrency());
        $this->assertEquals('CHF', $franc->getCurrency());
    }

    /**
     * @return \Dough\Money\Sum
     */
    public function testAddition()
    {
        $five = new Money(5, 'USD');
        $six = new Money(6, 'USD');
        $sum = $five->plus($six);

        $this->assertInstanceOf('Dough\Money\Sum', $sum);
        $this->assertEquals($five, $sum->getAugend());
        $this->assertEquals($six, $sum->getAddend());

        return $sum;
    }

    /**
     * @depends testAddition
     * @param \Dough\Money\Sum $sum
     */
    public function testReduceSum(Sum $sum)
    {
        $bank = new Bank(array('USD'), 'USD');
        $reduced = $bank->reduce($sum, 'USD');

        $this->assertEquals(new Money(11, 'USD'), $reduced);
    }

    public function testSubtraction()
    {
        $five = new Money(5, 'USD');
        $six = new Money(6, 'USD');
        $subtraction = $six->subtract($five);

        $this->assertInstanceOf('Dough\Money\Sum', $subtraction);
        $this->assertEquals($six, $subtraction->getAugend());
        $this->assertEquals($five->times(-1), $subtraction->getAddend());

        return $subtraction;
    }

    /**
     * @depends testSubtraction
     * @param \Dough\Money\Sum $subtraction
     */
    public function testReduceSubtraction(Sum $subtraction)
    {
        $bank = new Bank(array('USD'), 'USD');
        $reduced = $bank->reduce($subtraction, 'USD');

        $this->assertEquals(new Money(1, 'USD'), $reduced);
    }

    public function testReduceMoney()
    {
        $bank = new Bank(array('USD'), 'USD');
        $five = new Money(5, 'USD');
        $result = $bank->reduce($five, 'USD');

        $this->assertTrue($five->equals($result));
    }

    public function testReduceDifferentCurrencies()
    {
        $bank = $this->getBank();
        $result = $bank->reduce(new Money(2, 'CHF'), 'USD');

        $this->assertTrue($result->equals(new Money(1, 'USD')));
    }

    public function testMixedAddition()
    {
        $bank = $this->getBank();

        $fiveDollars = new Money(5, 'USD');
        $tenFrancs = new Money(10, 'CHF');

        $result = $bank->reduce($fiveDollars->plus($tenFrancs), 'USD');
        $this->assertTrue($result->equals(new Money(10, 'USD')));
    }

    public function testSumPlusMoney()
    {
        $bank = $this->getBank();

        $fiveDollars = new Money(5, 'USD');
        $tenFrancs = new Money(10, 'CHF');

        $sum = $fiveDollars->plus($tenFrancs)->plus($fiveDollars);
        $result = $bank->reduce($sum, 'USD');

        $this->assertTrue($result->equals(new Money(15, 'USD')));
    }

    public function testSumMultiply()
    {
        $bank = $this->getBank();

        $fiveDollars = new Money(5, 'USD');
        $tenFrancs = new Money(10, 'CHF');

        $sum = $fiveDollars->plus($tenFrancs)->times(2);
        $result = $bank->reduce($sum, 'USD');

        $this->assertTrue($result->equals(new Money(20, 'USD')));
    }
}