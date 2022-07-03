<?php

namespace App\DataTransferObjects;

use App\Models\Product;
use Spatie\DataTransferObject\DataTransferObject;

class CartItemObject extends DataTransferObject
{
    public Product $product;
    public int $quantity;
}
