<?php

namespace Dough;

class Money implements MoneyInterface
{
    private $amount;
    private $currency;

    public function __construct($amount, $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getCurrency()
    {
        return $this->currency;
    }

    public function equals(Money $money)
    {
        return $money->currency == $this->currency && $money->amount == $this->amount;
    }

    public function times($multiplier)
    {
        return new Money($this->amount * $multiplier, $this->currency);
    }

    public function plus(MoneyInterface $addend)
    {
        return new Sum($this, $addend);
    }

    public function reduce(Bank $bank, $toCurrency)
    {
        $rate = $bank->getRate($this->currency, $toCurrency);
        return new Money((float) $this->amount * $rate, $toCurrency);
    }
}