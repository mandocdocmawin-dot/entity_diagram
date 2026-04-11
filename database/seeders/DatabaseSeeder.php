<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Boss',
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin',
        ]);

        User::factory()->create([
            'name' => 'Regular Juan',
            'email' => 'user@test.com',
            'password' => bcrypt('password123'),
            'role' => 'user', 
        ]);

        Product::factory(10)->create();

        Customer::factory(5)->create()->each(function ($customer) {
            Order::factory(3)->create([
                'customer_id' => $customer->id,
            ]);
        });
    }
}