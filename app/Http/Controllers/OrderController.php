<?php

namespace App\Http\Controllers;

use App\Aggregates\CartAggregate;
use App\DataTransferObjects\CustomerObject;
use App\Http\Requests\OrderRequest;
use OpenApi\Attributes as OA;

class OrderController extends Controller
{
    #[OA\Post(
        path: '/api/order/{uuid}',
        operationId: 'placeOrder',
        description: 'Generate order from cart',
        requestBody: new OA\RequestBody(
            content: [
                new OA\JsonContent(
                    ref: '#/components/schemas/OrderRequest'
                )
            ]
        ),
        tags: ['Order'],
        parameters: [
            new OA\Parameter(
                ref: '#/components/parameters/uuid'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The order',
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'status',
                                type: 'bool'
                            ),
                        ],
                        type: 'object'
                    ),
                ]
            ),
        ],
    )]
    public function __invoke(OrderRequest $request, string $uuid)
    {
        $customer = new CustomerObject(...$request->all());
        CartAggregate::retrieve($uuid)
            ->createOrder($customer)
            ->persist();

        return ['success' => true];
    }
}
