<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ClassSectionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();

        // Using course_id = 1 for BSIT based on previous context
        $sections = [
            // --- 1st Year ---
            ['course_id' => 1, 'year_level' => 1, 'block' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 1, 'block' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 1, 'block' => '1N', 'created_at' => $now, 'updated_at' => $now],

            // --- 2nd Year ---
            ['course_id' => 1, 'year_level' => 2, 'block' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 2, 'block' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 2, 'block' => '1N', 'created_at' => $now, 'updated_at' => $now],

            // --- 3rd Year ---
            ['course_id' => 1, 'year_level' => 3, 'block' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 3, 'block' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 3, 'block' => '1N', 'created_at' => $now, 'updated_at' => $now],

            // --- 4th Year ---
            ['course_id' => 1, 'year_level' => 4, 'block' => '1', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 4, 'block' => '2', 'created_at' => $now, 'updated_at' => $now],
            ['course_id' => 1, 'year_level' => 4, 'block' => '1N', 'created_at' => $now, 'updated_at' => $now],
        ];

        DB::table('class_sections')->insert($sections);
    }
}