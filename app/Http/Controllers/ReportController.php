<?php

namespace App\Http\Controllers;

use App\EventQueries\RemovedByCustomerProducts;
use App\EventQueries\RemovedProducts;
use App\Models\Product;
use OpenApi\Attributes as OA;

class ReportController extends Controller
{
    #[OA\Get(
        path: '/api/reports/removed',
        operationId: 'removedReport',
        description: 'Get all removed from cart products',
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of products',
            ),
        ],
    )]
    public function removed()
    {
        $report = new RemovedProducts();
        return $report->getRemovedProducts()->map(function ($removed, $product_id) {
            $product = Product::find($product_id);
            $product->removed = $removed;
            return $product;
        });
    }

    #[OA\Get(
        path: '/api/reports/removed-by-customer',
        operationId: 'removedByCustomerReport',
        description: 'Get all removed from cart products with customer data',
        tags: ['Reports'],
        responses: [
            new OA\Response(
                response: 200,
                description: 'A list of customers with products',
            ),
        ],
    )]
    public function removedByCustomer()
    {
        $report = new RemovedByCustomerProducts();
        return $report->getRemovedProducts();
    }
}
