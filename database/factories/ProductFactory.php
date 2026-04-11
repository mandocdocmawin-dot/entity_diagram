<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
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
        return [
            'product_name' => $this->faker->randomElement([
                'Wireless Mouse', 
                'Mechanical Keyboard', 
                'Gaming Monitor', 
                'Laptop Stand', 
                'Bluetooth Headphones', 
                'Smartwatch', 
                'USB-C Hub', 
                'External Hard Drive', 
                '1080p Webcam', 
                'Ergonomic Desk Chair'
            ]),
            'price' => $this->faker->randomFloat(2, 10, 5000), // Random na presyo
        ];
    }
}
