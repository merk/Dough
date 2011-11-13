<?php

class MoneyText extends PHPUnit_Framework_TestCase
{
    protected function getBank()
    {
        $bank = new Bank();
        $bank->addRate('CHF', 'USD', 0.5);

        return $bank;
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

    public function testEquality()
    {
        $fiveDollars = new Money(5, 'USD');
        $sixDollars = new Money(6, 'USD');
        $fiveFrancs = new Money(5, 'CHF');

        $this->assertTrue($fiveDollars->equals($fiveDollars));
        $this->assertFalse($fiveDollars->equals($sixDollars));
        $this->assertFalse($fiveDollars->equals($fiveFrancs));
    }

    public function testCurrency()
    {
        $dollar = new Money(1, 'USD');
        $franc = new Money(1, 'CHF');

        $this->assertEquals('USD', $dollar->getCurrency());
        $this->assertEquals('CHF', $franc->getCurrency());
    }

    public function testAddition()
    {
        $five = new Money(5, 'USD');
        $six = new Money(6, 'USD');
        $sum = $five->plus($six);

        $this->assertInstanceOf('Sum', $sum);
        $this->assertEquals($five, $sum->getAugend());
        $this->assertEquals($six, $sum->getAddend());

        return $sum;
    }

    /**
     * @depends testAddition
     * @param Sum $sum
     */
    public function testReduceSum(Sum $sum)
    {
        $bank = new Bank();
        $reduced = $bank->reduce($sum, 'USD');

        $this->assertEquals(new Money(11, 'USD'), $reduced);
    }

    public function testReduceMoney()
    {
        $bank = new Bank();
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
}