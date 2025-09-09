<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
         // Create 100 categories
        $categories = Category::factory(100)->create();

        // Create 100 products
        $products = Product::factory(100)->create();

        // Attach random categories to each product (1–5 categories per product)
        $products->each(function ($product) use ($categories) {
            $randomCategories = $categories->random(rand(1, 5))->pluck('id');
            $product->categories()->attach($randomCategories);
        });
    }
}
