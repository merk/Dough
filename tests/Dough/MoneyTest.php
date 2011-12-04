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
        $bank = new Bank('Dough\\Money\\Money');

        return $bank;
    }

    public function testEquality()
    {
        $five = new Money(5);
        $six = new Money(6);

        $this->assertTrue($five->equals($five));
        $this->assertFalse($six->equals($five));
    }

    public function testDollarMultiplication()
    {
        $five = new Money(5);

        $this->assertEquals(new Money(10), $five->times(2));
        $this->assertEquals(new Money(15), $five->times(3));
        $this->assertEquals(new Money(1), $five->times(0.2));
    }

    public function testDivide()
    {
        $ten = new Money(10);

        $this->assertEquals(new Money(5), $ten->divide(2));
        $this->assertEquals(new Money(20), $ten->divide(.5));
    }

    public function testOddDivision()
    {
        $ten = new Money(10);

        $this->assertEquals(new Money(3.3333333333333), $ten->divide(3));
    }

    /**
     * @return \Dough\Money\Sum
     */
    public function testAddition()
    {
        $five = new Money(5);
        $six = new Money(6);
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
        $bank = $this->getBank();
        $reduced = $bank->reduce($sum);

        $this->assertEquals(new Money(11), $reduced);
    }

    public function testSubtraction()
    {
        $five = new Money(5);
        $six = new Money(6);
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
        $bank = $this->getBank();
        $reduced = $bank->reduce($subtraction);

        $this->assertEquals(new Money(1), $reduced);
    }

    public function testReduceMoney()
    {
        $bank = $this->getBank();
        $five = new Money(5);
        $result = $bank->reduce($five);

        $this->assertTrue($five->equals($result));
    }

    public function testSumPlusMoney()
    {
        $bank = $this->getBank();

        $five = new Money(5);

        $sum = $five->plus($five)->plus($five);
        $result = $bank->reduce($sum);

        $this->assertTrue($result->equals(new Money(15)));
    }

    public function testSumMultiply()
    {
        $bank = $this->getBank();

        $five = new Money(5, 'USD');

        $sum = $five->plus($five)->times(2);
        $result = $bank->reduce($sum, 'USD');

        $this->assertTrue($result->equals(new Money(20, 'USD')));
    }
}