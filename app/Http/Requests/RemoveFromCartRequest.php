<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'RemoveFromCartRequest',
    properties: [
        new OA\Property(
            property: 'product_id',
            description: 'The product ID',
            type: 'integer',
            example: 5
        ),
    ]
)]
class RemoveFromCartRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'product_id' => ['required', 'exists:products,id'],
        ];
    }
}
