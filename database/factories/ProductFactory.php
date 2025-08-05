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
        $productName = $this->faker->sentence(3); // Ambil nama produk singkat
        return [
            'name' => $this->faker->sentence(10),
             'image_url' => 'https://picsum.photos/seed/' . Str::random(8) . '/640/480',
            'condition' => $this->faker->randomElement(['baru', 'bekas']),
            'description' => $this->faker->paragraphs(3, true),
            'price' => $this->faker->numberBetween(100, 1000000),
            'stock' => $this->faker->numberBetween(0, 100),
            'sku' => strtoupper(Str::random(10)),
            'product_weight' => $this->faker->numberBetween(1, 500000),
            'shipping_insurance' => $this->faker->randomElement(['wajib', 'opsional']),
            'view' => $this->faker->numberBetween(0, 10000),
            'seller_id' => \App\Models\Seller::inRandomOrder()->first()->id,
        ];
    }
}
