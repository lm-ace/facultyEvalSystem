<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'role'          => 'admin',         
            'username'      => 'admin',          
            'password_hash' => Hash::make('admin123'), 
            'is_active'     => true,
            'last_login'    => now(),
        ]);
    }
}