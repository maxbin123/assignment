<?php

namespace Tests\Feature;

use App\Aggregates\CartAggregate;
use App\Models\Product;
use Tests\TestCase;

class CartAggregateTest extends TestCase
{
    public function test_cart_add_one()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 4);

        $this->assertEquals([$product->id => 4], $cart->getCart());
    }

    public function test_cart_add_same()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 2);
        $cart->addProduct($product, 4);

        $this->assertEquals([$product->id => 6], $cart->getCart());
    }

    public function test_cart_add_two()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 2);
        $cart->addProduct($product2, 4);

        $this->assertEquals([$product1->id => 2, $product2->id => 4], $cart->getCart());
    }

    public function test_cart_add_and_remove()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 4);
        $cart->removeProduct($product);

        $this->assertEquals([], $cart->getCart());
    }

    public function test_cart_add_two_and_remove_one()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 4);
        $cart->addProduct($product2, 1);
        $cart->removeProduct($product1);

        $this->assertEquals([$product2->id => 1], $cart->getCart());
    }
}
