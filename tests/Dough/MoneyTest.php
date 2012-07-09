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
use Dough\Money\MoneyInterface;
use Dough\Money\Sum;

class MoneyText extends PHPUnit_Framework_TestCase
{
    protected function getBank()
    {
        $bank = new Bank();

        return $bank;
    }

    public function testEquality()
    {
        $five = new Money(5);
        $six = new Money(6);

        $this->compareMoney($five, $five);
        $this->compareNotMoney($five, $six);
    }

    public function testDollarMultiplication()
    {
        $five = new Money(5);

        $this->compareMoney(new Money(10), $five->times(2));
        $this->compareMoney(new Money(15), $five->times(3));
        $this->compareMoney(new Money(1), $five->times(0.2));
    }

    public function testDivide()
    {
        $ten = new Money(10);

        $this->compareMoney(new Money(5), $ten->divide(2));
        $this->compareMoney(new Money(20), $ten->divide(.5));
    }

    public function testOddDivision()
    {
        $ten = new Money(10);

        $this->compareMoney(new Money(3.33), $ten->divide(3));
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
        $this->compareMoney($five, $sum->getAugend());
        $this->compareMoney($six, $sum->getAddend());

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

        $this->compareMoney(new Money(11), $reduced);
    }

    public function testSubtraction()
    {
        $five = new Money(5);
        $six = new Money(6);
        $subtraction = $six->subtract($five);

        $this->assertInstanceOf('Dough\Money\Sum', $subtraction);
        $this->compareMoney($six, $subtraction->getAugend());
        $this->compareMoney($five->times(-1), $subtraction->getAddend());

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

        $this->compareMoney(new Money(1), $reduced);
    }

    public function testReduceMoney()
    {
        $bank = $this->getBank();
        $five = new Money(5);
        $result = $bank->reduce($five);

        $this->compareMoney($five, $result);
    }

    public function testSumPlusMoney()
    {
        $bank = $this->getBank();

        $five = new Money(5);

        $sum = $five->plus($five)->plus($five);
        $result = $bank->reduce($sum);

        $this->compareMoney(new Money(15), $result);
    }

    public function testSumMultiply()
    {
        $bank = $this->getBank();

        $five = new Money(5);

        $sum = $five->plus($five)->times(2);
        $result = $bank->reduce($sum);

        $this->compareMoney(new Money(20), $result);
    }

    public function testRounding()
    {
        $bank = $this->getBank();

        $item = new Money(9.95);
        $discountedItem = $bank->reduce($item->times(0.90));
        $items = $bank->reduce($discountedItem->times(20)->reduce());

        $this->compareMoney(new Money(8.96), $discountedItem);
        $this->compareMoney(new Money(179.2), $items);
    }

    protected function compareMoney(MoneyInterface $expected, MoneyInterface $actual)
    {
        $expected = $expected->reduce($this->getBank());
        $actual = $actual->reduce($this->getBank());

        $this->assertEquals($expected->getAmount(), $actual->getAmount(), 'That both money instances contain the same amount');
    }

    protected function compareNotMoney(MoneyInterface $expected, MoneyInterface $actual)
    {
        $expected = $expected->reduce($this->getBank());
        $actual = $actual->reduce($this->getBank());

        $this->assertNotEquals($expected->getAmount(), $actual->getAmount(), 'That both money instances contain the same amount');
    }
}
