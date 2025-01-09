<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            'name' => $this->faker->sentence(10), // Nama produk acak
            'image_url' => $this->faker->imageUrl(640, 480, 'products', true),
            'condition' => $this->faker->randomElement(['baru', 'bekas']),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(100, 1000000),
            'stock' => $this->faker->numberBetween(0, 100),
            'sku' => strtoupper(Str::random(10)),
            'product_weight' => $this->faker->numberBetween(1, 500000),
            'shipping_insurance' => $this->faker->randomElement(['wajib', 'opsional']),
            'seller_id' => \App\Models\Seller::inRandomOrder()->first()->id,
        ];
    }
}
