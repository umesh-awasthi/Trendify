<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        // Get category IDs
        $smartphones = Category::where('name', 'Smartphones')->first();
        $laptops = Category::where('name', 'Laptops')->first();
        $androidPhones = Category::where('name', 'Android Phones')->first();
        $iPhones = Category::where('name', 'iPhones')->first();

        $products = [
            [
                'name' => 'iPhone 15 Pro',
                'description' => 'Latest iPhone with advanced features',
                'short_notes' => 'Premium smartphone',
                'price' => 999.99,
                'category_id' => $iPhones->id,
                'image' => 'products/iphone.jpg'
            ],
            [
                'name' => 'Samsung Galaxy S24',
                'description' => 'Latest Samsung flagship phone',
                'short_notes' => 'Android flagship',
                'price' => 899.99,
                'category_id' => $androidPhones->id,
                'image' => 'products/samsung.jpg'
            ],
            [
                'name' => 'MacBook Pro 16',
                'description' => 'Powerful laptop for professionals',
                'short_notes' => 'Pro laptop',
                'price' => 1999.99,
                'category_id' => $laptops->id,
                'image' => 'products/macbook.jpg'
            ],
            [
                'name' => 'Dell XPS 15',
                'description' => 'Premium Windows laptop',
                'short_notes' => 'High-performance laptop',
                'price' => 1499.99,
                'category_id' => $laptops->id,
                'image' => 'products/dell.jpg'
            ],
            [
                'name' => 'Google Pixel 8',
                'description' => 'Pure Android experience',
                'short_notes' => 'Google flagship phone',
                'price' => 799.99,
                'category_id' => $androidPhones->id,
                'image' => 'products/pixel.jpg'
            ]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
} 