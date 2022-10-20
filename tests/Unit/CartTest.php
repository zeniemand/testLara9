<?php

namespace Tests\Unit;

use App\Cart;
use App\Convert;
use PHPUnit\Framework\TestCase;

class CartTest extends TestCase
{

    public function test_cart_contents()
    {
        $cart = new Cart(['Apple', 'KiwiGreen']);
        $this->assertTrue($cart->has('Apple'));
        $this->assertFalse($cart->has('Kiwi'));

    }

    public function test_take_one_from_cart()
    {
        $cart = new Cart(['KiwiGreen' , 'Apple']);
        $this->assertEquals('KiwiGreen', $cart->takeOne());
    }

    public function test_price_in_usd()
    {
        $convert = new Convert(100);
        $this->assertEquals(2.5, $convert->priceInUsd());
    }

    public function test_price_in_eur()
    {
        $convert = new Convert(100);
        $this->assertEquals(2.22, $convert->priceInEur());
    }

}
