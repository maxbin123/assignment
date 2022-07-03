<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'OrderRequest',
    properties: [
        new OA\Property(
            property: 'email',
            description: 'Customer email',
            type: 'string',
            example: 'mail@example.com'
        ),
        new OA\Property(
            property: 'name',
            description: 'Customer name',
            type: 'string',
            example: 'Mike Martin'
        ),
        new OA\Property(
            property: 'address',
            description: 'Customer address',
            type: 'string',
            example: 'Lovely str. 34, Hampton, 323005'
        ),
        new OA\Property(
            property: 'phone',
            description: 'Customer phone',
            type: 'string',
            example: '234-505-2334'
        ),
    ]
)]
class OrderRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'name' => ['required'],
            'address' => ['required'],
            'phone' => ['required']
        ];
    }
}
