<?php

namespace App\StorableEvents;

use App\Models\Product;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class AddedToCart extends ShouldBeStored
{
    public Product $product;
    public int $quantity;

    public function __construct(Product $product, int $quantity)
    {
        $this->product = $product;
        $this->quantity = $quantity;
    }
}
