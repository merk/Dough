<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Exchanger\HistoricalArrayExchanger;

class HistoricalArrayExchangerTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Exchanger\HistoricalArrayExchanger
     */
    protected $exchanger;

    protected function setUp()
    {
        $this->exchanger = new HistoricalArrayExchanger();

        $day = new DateTime('2013-05-08');
        $this->exchanger->addRate('USD', 'EUR', $day, 1.3179970422);
        $this->exchanger->addRate('EUR', 'USD', $day, 0.7587270441);
        $this->exchanger->addRate('GBP', 'EUR', $day, 0.8468440465);
        $this->exchanger->addRate('EUR', 'GBP', $day, 1.1808549687);

        $day = new DateTime('2013-05-09');
        $this->exchanger->addRate('USD', 'EUR', $day, 1.3103485500);
        $this->exchanger->addRate('EUR', 'USD', $day, 0.7631557268);
        $this->exchanger->addRate('GBP', 'EUR', $day, 0.8453767466);
        $this->exchanger->addRate('EUR', 'GBP', $day, 1.1829045500);


        $day = new DateTime('2013-05-10');
        $this->exchanger->addRate('USD', 'EUR', $day, 1.2953194834);
        $this->exchanger->addRate('EUR', 'USD', $day, 0.7720103132);
        $this->exchanger->addRate('GBP', 'EUR', $day, 0.8447508078);
        $this->exchanger->addRate('EUR', 'GBP', $day, 1.1837810521);
    }

    /**
     * @dataProvider getData
     */
    public function testRatesAtDates($from, $to, $day, $expectedRate)
    {
        $rate = $this->exchanger->getRateAt($from, $to, $day);

        $this->assertEquals($expectedRate, $rate);
    }

    public function getData()
    {
        return array(
            array('USD', 'EUR', new DateTime('2013-05-09'), 1.31034855)
        );
    }

    /**
     * @expectedException \Dough\Exception\NoExchangeRateException
     */
    public function testNoRateForToday()
    {
        $this->exchanger->getRate('USD', 'EUR');
    }

    /**
     * @expectedException \Dough\Exception\NoExchangeRateException
     */
    public function testNoRateFor1979()
    {
        $this->exchanger->getRateAt('USD', 'EUR', new DateTime('1979-01-01'));
    }

    public function testExchangingSameRate()
    {
        $this->assertEquals(1, $this->exchanger->getRate('USD', 'USD'));
    }
}
