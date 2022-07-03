<?php

namespace App\Http\Controllers;

use App\Aggregates\CartAggregate;
use App\Http\Requests\AddToCartRequest;
use App\Http\Requests\RemoveFromCartRequest;
use App\Http\Transformers\CartTransformer;
use App\Models\Product;
use OpenApi\Attributes as OA;

class CartController extends Controller
{
    #[OA\Put(
        path: '/api/cart/{uuid}',
        operationId: 'addToCart',
        description: 'Add to cart',
        requestBody: new OA\RequestBody(
            content: [
                new OA\JsonContent(
                    ref: '#/components/schemas/AddToCartRequest'
                ),
            ]
        ),
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(
                ref: '#/components/parameters/uuid'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The cart',
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(
                                    ref: '#/components/schemas/CartTransformer'
                                )
                            ),
                        ],
                        type: 'object',
                    ),
                ]
            ),
        ],
    )]
    public function add(AddToCartRequest $request, string $uuid): array
    {
        $product = Product::findOrFail($request->product_id);
        $cart = CartAggregate::retrieve($uuid)
            ->addProduct($product, $request->quantity)
            ->persist();
        return $this->returnCart($cart);
    }

    #[OA\Delete(
        path: '/api/cart/{uuid}',
        operationId: 'removeFromCart',
        description: 'Remove from cart',
        requestBody: new OA\RequestBody(
            content: [
                new OA\JsonContent(
                    ref: '#/components/schemas/RemoveFromCartRequest'
                ),
            ]
        ),
        tags: ['Cart'],
        parameters: [
            new OA\Parameter(
                ref: '#/components/parameters/uuid'
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'The cart',
                content: [
                    new OA\JsonContent(
                        properties: [
                            new OA\Property(
                                property: 'data',
                                type: 'array',
                                items: new OA\Items(
                                    ref: '#/components/schemas/CartTransformer'
                                )
                            ),
                        ],
                        type: 'object',
                    ),
                ]
            ),
        ],
    )]
    public function remove(RemoveFromCartRequest $request, string $uuid): array
    {
        $product = Product::findOrFail($request->product_id);
        $cart = CartAggregate::retrieve($uuid)
            ->removeProduct($product)
            ->persist();
        return $this->returnCart($cart);
    }

    private function returnCart(CartAggregate $cart): array
    {
        return $cart
            ->getCart()
            ->map(function ($quantity, $product_id) {
                $product = Product::findOrFail($product_id);
                $product->quantity = $quantity;
                return $product;
            })
            ->transformWith(new CartTransformer())
            ->toArray();
    }
}
