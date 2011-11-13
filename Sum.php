<?php

class Sum implements MoneyInterface
{
    private $augend;
    private $addend;

    public function __construct(MoneyInterface $augend, MoneyInterface $addend)
    {
        $this->augend = $augend;
        $this->addend = $addend;
    }

    public function getAugend()
    {
        return $this->augend;
    }

    public function getAddend()
    {
        return $this->addend;
    }

    public function reduce(Bank $bank, $toCurrency)
    {
        $amount = $this->augend->reduce($bank, $toCurrency)->getAmount() +
                  $this->addend->reduce($bank, $toCurrency)->getAmount();

        return new Money($amount, $toCurrency);
    }

    public function plus(MoneyInterface $addend)
    {
        return new Sum($this, $addend);
    }

    public function times($multiplier)
    {
        return new Sum($this->augend->times($multiplier), $this->addend->times($multiplier));
    }
}
