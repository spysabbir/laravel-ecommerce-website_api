<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin and Customer data
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@admin.com',
                'password' => Hash::make('admin123'),
                'phone' => '1234567890',
                'role' => 'admin',
                'created_at' => Carbon::now(),
            ],
            [
                'name' => 'Customer',
                'email' => 'customer@customer.com',
                'password' => Hash::make('customer123'),
                'phone' => '0987654321',
                'role' => 'customer',
                'created_at' => Carbon::now(),
            ],
        ];

        // Insert data
        User::insert($users);
    }
}
