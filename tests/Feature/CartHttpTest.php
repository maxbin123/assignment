<?php

namespace Tests\Feature;

use App\Models\Product;
use Tests\TestCase;

class CartHttpTest extends TestCase
{
    public function test_adding_to_cart_controller()
    {
        $uuid = $this->faker->uuid();
        [$product1, $product2] = Product::factory(2)->create();
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product2->id,
            'quantity' => 4
        ])
            ->assertJsonPath('data.0.product_id', $product1->id)
            ->assertJsonPath('data.0.quantity', 2)
            ->assertJsonPath('data.1.product_id', $product2->id)
            ->assertJsonPath('data.1.quantity', 4);
    }

    public function test_removing_from_cart_controller()
    {
        $uuid = $this->faker->uuid();
        [$product1, $product2] = Product::factory(2)->create();
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product2->id,
            'quantity' => 4
        ]);
        $this->delete(route('cart.remove', $uuid), [
            'product_id' => $product2->id,
        ])
            ->assertJsonPath('data.0.product_id', $product1->id)
            ->assertJsonPath('data.0.quantity', 2);
    }

    public function test_creating_order_from_controller()
    {
        $uuid = $this->faker->uuid();
        [$product1, $product2] = Product::factory(2)->create();
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product1->id,
            'quantity' => 2
        ]);
        $this->put(route('cart.add', $uuid), [
            'product_id' => $product2->id,
            'quantity' => 4
        ]);
        $this->post(route('order.place', $uuid), [
                'name' => $this->faker->name(),
                'email' => $this->faker->email(),
                'address' => $this->faker->streetAddress(),
                'phone' => $this->faker->phoneNumber(),
            ])->assertJsonPath('success', true);
        $this->assertDatabaseCount('orders', 1);
    }
}
