<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run()
    {
        // Parent Categories
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices and gadgets',
            'parent_id' => null
        ]);

        $clothing = Category::create([
            'name' => 'Clothing',
            'description' => 'Fashion and apparel',
            'parent_id' => null
        ]);

        // Electronics subcategories
        $smartphones = Category::create([
            'name' => 'Smartphones',
            'description' => 'Mobile phones and accessories',
            'parent_id' => $electronics->id
        ]);

        $laptops = Category::create([
            'name' => 'Laptops',
            'description' => 'Notebooks and laptops',
            'parent_id' => $electronics->id
        ]);

        // Smartphones subcategories
        Category::create([
            'name' => 'Android Phones',
            'description' => 'Android-based smartphones',
            'parent_id' => $smartphones->id
        ]);

        Category::create([
            'name' => 'iPhones',
            'description' => 'Apple iPhones',
            'parent_id' => $smartphones->id
        ]);

        // Clothing subcategories
        $mens = Category::create([
            'name' => "Men's Wear",
            'description' => 'Clothing for men',
            'parent_id' => $clothing->id
        ]);

        $womens = Category::create([
            'name' => "Women's Wear",
            'description' => 'Clothing for women',
            'parent_id' => $clothing->id
        ]);

        // Men's subcategories
        Category::create([
            'name' => 'Shirts',
            'description' => "Men's shirts and t-shirts",
            'parent_id' => $mens->id
        ]);

        Category::create([
            'name' => 'Pants',
            'description' => "Men's pants and trousers",
            'parent_id' => $mens->id
        ]);

        // Women's subcategories
        Category::create([
            'name' => 'Dresses',
            'description' => "Women's dresses",
            'parent_id' => $womens->id
        ]);

        Category::create([
            'name' => 'Skirts',
            'description' => "Women's skirts",
            'parent_id' => $womens->id
        ]);
    }
} 