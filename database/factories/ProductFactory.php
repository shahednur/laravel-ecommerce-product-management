<?php

namespace Database\Factories;

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
        $name = $this->faker->words(3, true);

        // Ensure products folder exists
        $dir = storage_path('app/public/products');
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::random(5),
            'sku' => strtoupper(Str::random(10)),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'stock' => $this->faker->numberBetween(0, 200),
            'description' => $this->faker->paragraph,
            'image' => null,
            'active' => $this->faker->boolean(90),
        ];
    }
}
