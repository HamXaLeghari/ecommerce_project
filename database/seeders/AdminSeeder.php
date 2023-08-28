<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Users;
use Illuminate\Support\Facades\Hash; 


class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::create([
            'name' => 'Khalid',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('admin123'), // You can replace 'password' with the desired password
            'phone' => '03456787688',
            'Current_address' => 'P Block Johar Town Lahore',
            'is_admin' => 1,
            'gender' => 'Male'
        ]);
    }
}
