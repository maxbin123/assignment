<?php

namespace Tests\Feature;

use App\Aggregates\CartAggregate;
use App\EventQueries\RemovedByCustomerProducts;
use App\EventQueries\RemovedProducts;
use App\Models\Product;
use Tests\TestCase;

class CartAggregateTest extends TestCase
{
    public function test_cart_add_one()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 4);

        $this->assertEquals([$product->id => 4], $cart->getCart()->toArray());
    }

    public function test_cart_add_same()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 2)
            ->addProduct($product, 4);

        $this->assertEquals([$product->id => 6], $cart->getCart()->toArray());
    }

    public function test_cart_add_two()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 2)
            ->addProduct($product2, 4);

        $this->assertEquals([$product1->id => 2, $product2->id => 4], $cart->getCart()->toArray());
    }

    public function test_cart_add_and_remove()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product, 4)
            ->removeProduct($product);

        $this->assertEquals([], $cart->getCart()->toArray());
        $this->assertEquals([$product->id], $cart->getRemovedProducts());
    }

    public function test_cart_add_two_and_remove_one()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 4)
            ->addProduct($product2, 1)
            ->removeProduct($product1);

        $this->assertEquals([$product2->id => 1], $cart->getCart()->toArray());
        $this->assertEquals([$product1->id], $cart->getRemovedProducts());
    }

    public function test_many_removals()
    {
        [$product1, $product2] = Product::factory(2)->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $cart->addProduct($product1, 4)
            ->addProduct($product2, 1)
            ->removeProduct($product1)
            ->removeProduct($product2)
            ->addProduct($product1, 1);

        $this->assertEquals([$product1->id => 1], $cart->getCart()->toArray());
        $this->assertEquals([$product1->id, $product2->id], $cart->getRemovedProducts());
    }

    public function test_order_created()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4)
            ->createOrder($customer);

        $this->assertEquals($customer, $cart->getCustomer());
    }

    public function test_cannot_reuse_uuid()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $this->expectExceptionMessage('This order already finished, reset the UUID');

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4)
            ->createOrder($customer)
            ->addProduct($product, 1);
    }

    public function test_order_projector()
    {
        $product = Product::factory()->create();
        $cart = CartAggregate::retrieve($this->faker->uuid());

        $customer = $this->getCustomer();
        $cart->addProduct($product, 4)
            ->createOrder($customer)
            ->persist();

        $this->assertDatabaseCount('order_product', 1);
        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseHas('orders', [
            'name' => $customer->name,
            'email' => $customer->email,
        ]);
    }

    public function test_removed_products_query_works()
    {
        [$product1, $product2] = Product::factory(2)->create();

        $customer1 = $this->getCustomer();
        $cart = CartAggregate::retrieve($this->faker->uuid());
        $cart->addProduct($product1, 4)
            ->removeProduct($product1)
            ->addProduct($product1, 1)
            ->createOrder($customer1)
            ->persist();

        $customer2 = $this->getCustomer();
        $cart = CartAggregate::retrieve($this->faker->uuid());
        $cart->addProduct($product2, 4)
            ->removeProduct($product2)
            ->addProduct($product1, 4)
            ->removeProduct($product1)
            ->addProduct($product2, 1)
            ->createOrder($customer2)
            ->persist();

        $cart = CartAggregate::retrieve($this->faker->uuid());
        $cart->addProduct($product2, 4)
            ->removeProduct($product2)
            ->persist();

        $report = new RemovedProducts();
        $this->assertEquals([$product1->id => 2, $product2->id => 2], $report->getRemovedProducts());

        $report = new RemovedByCustomerProducts();
        $removed = $report->getRemovedProducts();
        $this->assertEquals($product1->id, $removed[0]['product']->id);
        $this->assertEquals($customer1->name, $removed[0]['customer']->name);
    }
}
