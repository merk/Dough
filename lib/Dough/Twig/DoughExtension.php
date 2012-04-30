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

    public function getFunctions()
    {
        return array(
            'dough_currency' => new \Twig_Function_Method($this, 'renderCurrency'),
        );
    }



    public function renderCurrency(MoneyInterface $money)
    {
        $reduced = $money->reduce($this->bank);

        return number_format($reduced->getAmount(), 2);
    }

    public function getName()
    {
        return 'merk_dough';
    }
}
