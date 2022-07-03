<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AddToCartRequest',
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
class AddToCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
