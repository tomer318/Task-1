<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);
        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? 1,
            'name' => ucfirst($name),
            'slug' => Str::slug($name) . '-' . fake()->unique()->numberBetween(1, 99999),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 2000),
            'stock' => fake()->numberBetween(0, 500),
            'image' => null,
        ];
    }
}