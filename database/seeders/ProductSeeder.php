<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $products = json_decode(file_get_contents(base_path('products.json')));
        foreach ($products as $product) {
            Product::create([
                'name' => $product->name,
                'price' => $product->price,
            ]);
        }
    }
}
