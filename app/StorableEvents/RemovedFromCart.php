<?php

namespace App\StorableEvents;

use App\Models\Product;
use Spatie\EventSourcing\StoredEvents\ShouldBeStored;

class RemovedFromCart extends ShouldBeStored
{
    public Product $product;

    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
