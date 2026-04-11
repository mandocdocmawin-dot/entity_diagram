<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
        // Kukuha tayo ng random na ID mula sa mga Customers na gagawin natin
            'customer_id' => \App\Models\Customer::factory(), 
            'order_date' => $this->faker->date(), // Random na petsa
        ];
    }
}
