<?php

namespace App;

class Convert
{
    protected float $price;

    public function __construct(float $price)
    {
        $this->price = $price;
    }

    public function priceInEur() : float
    {
        return round($this->price / 45, 2);
    }

    public function priceInUsd() : float
    {
        return round($this->price / 40, 2);
    }
}
