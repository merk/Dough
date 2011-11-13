<?php

class Bank
{
    private $rates = array();

    public function addRate($fromCurrency, $toCurrency, $rate)
    {
        $this->rates["{$fromCurrency}-{$toCurrency}"] = $rate;
    }

    public function getRate($fromCurrency, $toCurrency)
    {
        if ($fromCurrency === $toCurrency) {
            return 1;
        }

        return $this->rates["{$fromCurrency}-{$toCurrency}"];
    }

    public function reduce(MoneyInterface $source, $toCurrency)
    {
        return $source->reduce($this, $toCurrency);
    }
}

