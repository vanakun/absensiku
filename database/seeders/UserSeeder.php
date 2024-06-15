<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Empty the table first
        DB::table('users')->delete();

        User::create([
            'email' => 'admin@test.com',
            'name' => "admin",
            'name_lengkap' => "admin vian",
            "password" => Hash::make('password')
        ]);
        User::create([
            'email' => 'admin1@test.com',
            'name' => "user",
            'name_lengkap' => "Mochamad Alvian Wicaksono",
            "password" => Hash::make('password')
        ]);
        User::create([
            'email' => 'admin2@test.com',
            'name' => "user",
            'name_lengkap' => "Rendi Panglila",
            "password" => Hash::make('password')
        ]);
    }
}
