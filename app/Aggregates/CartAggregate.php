<?php

namespace App\Aggregates;

use App\DataTransferObjects\CustomerObject;
use App\Models\Product;
use App\StorableEvents\AddedToCart;
use App\StorableEvents\OrderCreated;
use App\StorableEvents\RemovedFromCart;
use Spatie\EventSourcing\AggregateRoots\AggregateRoot;

class CartAggregate extends AggregateRoot
{
    public array $cart;
    public array $removed_products;
    public CustomerObject $customer;
    public bool $finished = false;

    public function addProduct(Product $product, int $quantity): static
    {
        if ($this->finished) {
            throw new \Exception('This order already finished, reset the UUID');
        }

        $this->recordThat(new AddedToCart($product, $quantity));

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        if ($this->finished) {
            throw new \Exception('This order already finished, reset the UUID');
        }

        $this->recordThat(new RemovedFromCart($product));

        return $this;
    }

    public function createOrder(CustomerObject $customer): static
    {
        $this->recordThat(new OrderCreated($customer, $this->cart));

        return $this;
    }

    public function applyAddedToCart(AddedToCart $event)
    {
        if (isset($this->cart[$event->product->id])) {
            $this->cart[$event->product->id] += $event->quantity;
        } else {
            $this->cart[$event->product->id] = $event->quantity;
        }
    }

    public function applyRemovedFromCart(RemovedFromCart $event)
    {
        if (isset($this->cart[$event->product->id])) {
            unset($this->cart[$event->product->id]);
        }

        $this->removed_products[] = $event->product->id;
    }

    public function applyOrderCreater(OrderCreated $event)
    {
        $this->customer = $event->customer;
        $this->finished = true;
    }

    public function getCart(): array
    {
        return $this->cart;
    }

    public function getRemovedProducts(): array
    {
        return $this->removed_products;
    }

    public function getCustomer()
    {
        return $this->customer;
    }
}
