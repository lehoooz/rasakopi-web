<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Owner Rasakopi',
            'email' => 'admin@rasakopi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'cup_count' => 0,
        ]);

        User::create([
            'name' => 'Budi Kasir',
            'email' => 'kasir@rasakopi.com',
            'password' => Hash::make('password'),
            'role' => 'kasir',
            'cup_count' => 0,
        ]);

        User::create([
            'name' => 'Member Setia',
            'email' => 'member@rasakopi.com',
            'password' => Hash::make('password'),
            'role' => 'customer',
            'cup_count' => 8,
        ]);

        Product::create([
            'name' => 'Kopi Susu Gula Aren',
            'price' => 18000,
            'status' => 'available',
        ]);

        Product::create([
            'name' => 'Americano',
            'price' => 15000,
            'status' => 'available',
        ]);
        
        Product::create([
            'name' => 'Cafe Latte',
            'price' => 22000,
            'status' => 'sold_out',
        ]);
    }
}