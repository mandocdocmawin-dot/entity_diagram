<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Order;
use App\Models\CustomerProfile;
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

        $products = Product::factory(10)->create();

        Customer::factory(5)->create()->each(function ($customer) use ($products){

            CustomerProfile::create([
                'customer_id' => $customer->id,
                'shipping_address' => 'Random Street, City ' . rand(1, 100),
                'phone_number' => '09' . rand(100000000, 999999999),
            ]);

            Order::factory(3)->create([
                'customer_id' => $customer->id,  
            ])->each(function ($order) use ($products) {
                $order->products()->attach(
                    $products->random(2)->pluck('id')->toArray(), 
                    ['quantity' => rand(1, 5)]
                );
            });
        });
    }


}