<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    private object $product;

    public function setUp(): void
    {
        parent::setUp();
        $this->product = Product::factory()->create();
    }

    public function test_products_route_return_ok()
    {
        $response = $this->get('/products');
        $response->assertSee('Products index');
        $response->assertStatus(200);
        //$response->assertStatus(404);
    }

    public function test_product_has_name()
    {
        $this->assertNotEmpty($this->product->name);
    }

    public function test_products_are_empty()
    {
        $response = $this->get('/products');
        $response->assertSee('No products');
    }

    public function test_products_are_not_empty()
    {
        $response = $this->get('/products');
        //$response->assertDontSee('No products');
        $response->assertSee($this->product->name);
    }

    public function test_auth_user_can_see_the_buy_button()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/products');
        $response->assertSee('Buy Product');
    }

    public function test_unauth_user_cannot_see_the_buy_button()
    {
        $response = $this->get('/products');
        $response->assertDontSee('Buy Product');
    }

    public function test_auth_user_can_see_create_link()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products');
        $response->assertSee('Create');
    }

    public function test_unauth_user_cannot_see_create_link()
    {
        $response = $this->get('/products');
        $response->assertDontSee('Create');
    }

    public function test_auth_user_can_visit_the_public_create_route()
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_unauth_user_cannot_visit_the_public_create_route()
    {
        $response = $this->get('/products/create');
        $response->assertStatus(302);
        $response->assertRedirect('/login');
    }
}
