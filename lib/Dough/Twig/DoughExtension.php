<?php

namespace Dough\Twig;

use Dough\Bank\BankInterface;
use Dough\Money\MoneyInterface;

class DoughExtension extends \Twig_Extension
{
    private $bank;

    public function __construct(BankInterface $bank = null)
    {
        $this->bank = $bank;
    }

    public function getFilters()
    {
        return array(
            'dough_currency' => new \Twig_Filter_Method($this, 'getAmount', array('is_safe' => array('html'))),
        );
    }

    public function getAmount(MoneyInterface $money, $currency = null)
    {
        $reduced = $this->bank->reduce($money, $currency);

        return $reduced->getAmount();
    }

    public function getName()
    {
        return 'merk_dough';
    }
}
