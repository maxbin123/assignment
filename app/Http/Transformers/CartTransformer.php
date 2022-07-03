<?php

namespace App\Http\Transformers;

use App\Models\Product;
use League\Fractal\TransformerAbstract;

use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'CartTransformer',
    properties: [
        new OA\Property(
            property: 'product_id',
            description: 'The product ID',
            type: 'integer',
            example: 5
        ),
        new OA\Property(
            property: 'quantity',
            description: 'The quantity of the product',
            type: 'integer',
            example: 2
        ),
    ]
)]
class CartTransformer extends TransformerAbstract
{
    public function transform(Product $product)
    {
        return [
            'product_id' => $product->id,
            'quantity' => $product->quantity,
        ];
    }
}
