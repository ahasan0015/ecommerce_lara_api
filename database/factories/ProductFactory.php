<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = \App\Models\Product::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);

        return [
            'category_id' => rand(1, 5),
            'brand_id' => rand(1, 5),
            'status_id' => rand(1, 2),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'base_price' => $this->faker->randomFloat(2, 10, 1000),
        ];
    }
}
