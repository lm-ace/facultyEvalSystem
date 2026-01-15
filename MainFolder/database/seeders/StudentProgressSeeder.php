<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str; 

class StudentProgressSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Department & Course
        $deptId = DB::table('departments')->insertGetId(['name'=>'CCIS', 'code'=>'CCIS']);
        $courseId = DB::table('courses')->insertGetId(['department_id'=>$deptId, 'name'=>'BSIT', 'code'=>'BSIT']);

        // 2. Create Student User
        $userId = DB::table('users')->insertGetId([
            'role'=>'student', 'username'=>'2023-0001-MN-0', 'password_hash'=>Hash::make('password123'), 'is_active'=>1
        ]);
        $studentId = DB::table('students')->insertGetId([
            'user_id'=>$userId, 'student_number'=>'2023-0001-MN-0', 'first_name'=>'Juan', 'last_name'=>'Dela Cruz', 'year_level'=>3, 'block_section'=>'1'
        ]);

        // 3. Create Active Period
        $periodId = DB::table('review_periods')->insertGetId([
            'name'=>'1st Sem', 'academic_year'=>'2025-2026', 'semester'=>'1st', 'start_date'=>'2025-01-01', 'end_date'=>'2025-05-01', 'is_open'=>true
        ]);

        // 4. Enroll Student
        $sectionId = DB::table('class_sections')->insertGetId(['course_id'=>$courseId, 'year_level'=>3, 'block'=>'1']);
        DB::table('enrollments')->insert(['student_id'=>$studentId, 'class_section_id'=>$sectionId]);

        // 5. Create Questions (IDs 1-20)
        $adminId = DB::table('admins')->insertGetId(['user_id' => $userId, 'first_name'=>'Admin', 'last_name'=>'User']);
        $templateId = DB::table('criteria_templates')->insertGetId(['name'=>'Standard Eval', 'created_by'=>$adminId]);
        $criteriaSectionId = DB::table('criteria_sections')->insertGetId(['template_id'=>$templateId, 'section_number'=>1, 'section_name'=>'Instruction', 'position'=>1]);

        for($i=1; $i<=20; $i++) {
            DB::table('criteria_items')->insert([
                'section_id' => $criteriaSectionId, 'item_number' => $i, 'question_text' => "Question Number $i", 'max_score' => 5, 'position' => $i
            ]);
        }

        // 6. INSERT THE ORIGINAL PROFESSORS
        $originalList = [
            ['first'=>'Danilo', 'last'=>'Villamor', 'code'=>'COMP 101', 'name'=>'INTRO TO COMPUTING', 'img'=>'faculty1.jpg'],
            ['first'=>'Danica', 'last'=>'Santos', 'code'=>'COMP 102', 'name'=>'WEB DEVELOPMENT', 'img'=>'faculty2.jpg'],
            ['first'=>'April', 'last'=>'Dela Cruz', 'code'=>'COMP 103', 'name'=>'MULTIMEDIA', 'img'=>'faculty3.jpg'],
            ['first'=>'Michael', 'last'=>'Johnson', 'code'=>'ENGL 102', 'name'=>'TECHNICAL WRITING', 'img'=>'faculty1.jpg'],
            ['first'=>'Sarah', 'last'=>'Lee', 'code'=>'PHYS 201', 'name'=>'GENERAL PHYSICS', 'img'=>'faculty2.jpg'],
            ['first'=>'Robert', 'last'=>'Chen', 'code'=>'HIST 105', 'name'=>'WORLD HISTORY', 'img'=>'faculty3.jpg'],
            ['first'=>'Maria', 'last'=>'Garcia', 'code'=>'PSYC 101', 'name'=>'PSYCHOLOGY', 'img'=>'faculty1.jpg'],
        ];

        foreach($originalList as $index => $prof) {
            // Create Faculty User
            $fUser = DB::table('users')->insertGetId(['role'=>'faculty', 'username'=>'FAC'.$index, 'password_hash'=>Hash::make('password123')]);
            
            // Create Faculty Profile
            $fId = DB::table('faculties')->insertGetId([
                'user_id'=>$fUser, 
                'faculty_code'=>'F00'.$index, 
                'first_name'=>$prof['first'], 
                'last_name'=>$prof['last'], 
                'email' => strtolower($prof['first'] . '.' . $prof['last'] . '@pup.edu.ph'), 
                'profile_picture'=>$prof['img'], 
                'department_id'=>$deptId
            ]);

            // Create Subject
            $subId = DB::table('subjects')->insertGetId([
                'course_id'=>$courseId, 'name'=>$prof['name'], 'subject_code'=>$prof['code'], 'year_level'=>3
            ]);

            // Create Class Offering
            DB::table('class_offerings')->insertGetId([
                'class_section_id'=>$sectionId, 'subject_id'=>$subId, 'faculty_id'=>$fId, 'semester_id'=>$periodId
            ]);
        }
    }
}