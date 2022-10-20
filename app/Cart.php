<?php

namespace App;

class Cart
{

    protected array $items = [];

    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    public function has($item) : bool
    {
        return in_array($item, $this->items);
    }

    public function takeOne() : string
    {
        return array_shift($this->items);
    }
}
