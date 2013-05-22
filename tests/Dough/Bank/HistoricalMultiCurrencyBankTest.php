<?php

/*
 * This file is part of Dough.
 *
 * (c) Tim Nagel <tim@nagel.com.au>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Dough\Bank\HistoricalMultiCurrencyBank;
use Dough\Exchanger\HistoricalArrayExchanger;
use Dough\Money\MultiCurrencyMoney;

class HistoricalMultiCurrencyBankText extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Dough\Bank\HistoricalMultiCurrencyBank
     */
    protected $bank;

    /**
     * @var \Dough\Exchanger\ArrayExchanger
     */
    protected $exchanger;

    protected function setUp()
    {
        $this->exchanger = new HistoricalArrayExchanger();
        $this->bank = new HistoricalMultiCurrencyBank(array('USD', 'EUR'), 'USD', $this->exchanger);

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


        $day = new DateTime;
        $this->exchanger->addRate('USD', 'EUR', $day, 1.2953194834);
        $this->exchanger->addRate('EUR', 'USD', $day, 0.7720103132);
        $this->exchanger->addRate('GBP', 'EUR', $day, 0.8447508078);
        $this->exchanger->addRate('EUR', 'GBP', $day, 1.1837810521);
    }

    public function testGetRates()
    {
        $this->assertEquals(1, $this->bank->getRate('USD', 'USD'));

        $this->assertEquals(1.2953194834, $this->bank->getRate('USD', 'EUR'));

        $date = new DateTime('2013-05-08');
        $this->assertEquals(1.3179970422, $this->bank->getRateAt($date, 'USD', 'EUR'));
        $this->assertEquals(0.7587270441, $this->bank->getRateAt($date, 'EUR'));
    }

    public function testReduceAt()
    {
        $money = new MultiCurrencyMoney(10, 'GBP');

        $date = new DateTime('2013-05-09');
        $reduced = $this->bank->reduceAt($date, $money);

        var_dump($reduced);
        $this->assertTrue($reduced->equals(new MultiCurrencyMoney(10, 'USD')));
    }
}
