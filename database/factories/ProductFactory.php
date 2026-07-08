<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = "Batik " . $this->faker->words(2, true);
        return [
            "name" => $name,
            "slug" => Str::slug($name),
            "description" => $this->faker->paragraph,
            "price" => $this->faker->numberBetween(100000, 500000),
            "stock" => $this->faker->numberBetween(10, 100),
            "is_active" => true,
            "is_featured" => $this->faker->boolean,
            "category_id" => Category::inRandomOrder()->first()->id,
        ];
    }
}
