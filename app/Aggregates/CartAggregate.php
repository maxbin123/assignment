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
    private array $cart;

    public function addProduct(Product $product, int $quantity): static
    {
        $this->recordThat(new AddedToCart($product, $quantity));

        return $this;
    }

    public function removeProduct(Product $product): static
    {
        $this->recordThat(new RemovedFromCart($product));

        return $this;
    }

    public function createOrder(CustomerObject $customer): static
    {
        $this->recordThat(new OrderCreated($customer));

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
    }

    public function getCart(): array
    {
        return $this->cart;
    }
}
