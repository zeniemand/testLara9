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

    public function test_auth_admin_user_can_see_create_link()
    {
        $user = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($user)->get('/products');
        $response->assertSee('Create');
    }

    public function test_unauth_user_cannot_see_create_link()
    {
        $response = $this->get('/products');
        $response->assertDontSee('Create');
    }

    public function test_auth_admin_user_can_visit_the_public_create_route()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($admin)->get('/products/create');
        $response->assertStatus(200);
    }

    public function test_unauth_not_admin_user_cannot_visit_the_public_create_route()
    {
        $response = $this->get('/products/create');
        $response->assertStatus(403);
    }

    public function test_admin_can_store_new_product()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $response = $this->actingAs($admin)->post('/products', [
            'name' => 'Apple',
            'type' => 'Fruit',
            'price' => 2.99
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/products');
        $this->assertCount(2, Product::all());
        $this->assertDatabaseHas('products', ['name' => 'Apple', 'type' => 'Fruit', 'price' => 2.99]);
    }

    public function test_admin_can_see_the_edit_product_page()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $product = Product::factory()->create();
        $response = $this->actingAs($admin)->get('/products/' . $product->id . '/edit');
        $response->assertStatus(200);
        $response->assertSee($product->name);
    }

    public function test_admin_can_update_product()
    {
        $admin = User::factory()->create(['is_admin' => 1]);
        $this->assertCount(1, Product::all());
        $product = Product::first();
        $response = $this->actingAs($admin)->put('/products/' . $product->id, [
            'name' => 'Updated Product',
            'type' => 'Testik',
            'price' => 3.78
        ]);
        $response->assertSessionHasNoErrors();
        $response->assertRedirect('/products');
        $this->assertEquals('Updated Product', Product::first()->name);
    }
}
