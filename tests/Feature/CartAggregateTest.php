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
        $this->assertEquals([$product->id], $cart->getRemovedProducts());
    }

    public function test_cart_add_two_and_remove_one()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 4);
        $cart->addProduct($product2, 1);
        $cart->removeProduct($product1);

        $this->assertEquals([$product2->id => 1], $cart->getCart());
        $this->assertEquals([$product1->id], $cart->getRemovedProducts());
    }

    public function test_many_removals()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 4);
        $cart->addProduct($product2, 1);
        $cart->removeProduct($product1);
        $cart->removeProduct($product2);
        $cart->addProduct($product1, 1);

        $this->assertEquals([$product1->id => 1], $cart->getCart());
        $this->assertEquals([$product1->id, $product2->id], $cart->getRemovedProducts());
    }

    public function test_order_created()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4);
        $cart->createOrder($customer);

        $this->assertEquals($customer, $cart->getCustomer());
    }

    public function test_cannot_reuse_uuid()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $this->expectExceptionMessage('This order already finished, reset the UUID');

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4);
        $cart->createOrder($customer);
        $cart->addProduct($product, 1);
    }

    public function test_order_projector()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4);
        $cart->createOrder($customer)->persist();

        $this->assertDatabaseCount('order_product', 1);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'name' => $customer->name,
            'email' => $customer->email,
        ]);
    }
}
