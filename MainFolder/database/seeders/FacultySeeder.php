<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        $faculties = [
            ['first' => 'John', 'last' => 'Doe', 'code' => 'FAC001', 'email' => 'john.doe@example.com'],
            ['first' => 'Jane', 'last' => 'Smith', 'code' => 'FAC002', 'email' => 'jane.smith@example.com'],
            
        ];

        foreach ($faculties as $faculty) {
           
            $userId = DB::table('users')->insertGetId([
                'role'          => 'faculty',
                'username'      => $faculty['code'],
                'email'         => $faculty['email'],
                'password_hash' => Hash::make('password123'),
                'is_active'     => 1,
                'created_at'    => Carbon::now(),
                'updated_at'    => Carbon::now(),
            ]);

            // I-link ang User sa Faculty table
            DB::table('faculties')->insert([
                'user_id'         => $userId,
                'faculty_code'    => $faculty['code'],
                'first_name'      => $faculty['first'],
                'last_name'       => $faculty['last'],
                'email'           => $faculty['email'],
                'department_id'   => 1, 
                'created_at'      => Carbon::now(),
                'updated_at'      => Carbon::now(),
            ]);
        }
    }
}