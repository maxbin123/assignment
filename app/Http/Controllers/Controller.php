<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'A shopping cart API',
    title: 'Shopping Cart',
)]
#[OA\Parameter(
    name: 'uuid',
    description: "The cart's UUID",
    in: 'path',
    schema: new OA\Schema(
        schema: 'uuid',
        type: 'string',
        example: '12345678-1234-1234-1234-123456789012',
    ),
)]
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
