<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'description' => $this->faker->paragraph(3),
            'short_notes' => $this->faker->sentence(5),
            'price' => $this->faker->randomFloat(2, 10, 1000),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
} 