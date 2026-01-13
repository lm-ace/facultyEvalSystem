<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear tables first
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        // Truncate tables
        $tables = [
            'departments',
            'users',
            'subjects',
            'sections',
            'evaluation_criteria',
            'system_settings'
        ];
        
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }
        
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // 1. Insert Departments
        $departments = [
            ['code' => 'CCIS', 'name' => 'College of Computer and Information Sciences', 'description' => 'Computer and Information Sciences programs'],
            ['code' => 'CAF', 'name' => 'College of Accountancy and Finance', 'description' => 'Accountancy and Finance programs'],
            ['code' => 'CADBE', 'name' => 'College of Architecture and Design', 'description' => 'Architecture and Design programs'],
            ['code' => 'CAL', 'name' => 'College of Arts and Letters', 'description' => 'Arts and Letters programs'],
            ['code' => 'CBA', 'name' => 'College of Business Administration', 'description' => 'Business Administration programs'],
            ['code' => 'COC', 'name' => 'College of Communication', 'description' => 'Communication programs'],
            ['code' => 'COED', 'name' => 'College of Education', 'description' => 'Education programs'],
            ['code' => 'CE', 'name' => 'College of Engineering', 'description' => 'Engineering programs'],
            ['code' => 'CHK', 'name' => 'College of Human Kinetics', 'description' => 'Human Kinetics programs'],
            ['code' => 'CL', 'name' => 'College of Law', 'description' => 'Law programs'],
            ['code' => 'CPSPA', 'name' => 'College of Political Science and Public Administration', 'description' => 'Political Science and Public Administration programs'],
            ['code' => 'CSSD', 'name' => 'College of Social Sciences and Development', 'description' => 'Social Sciences and Development programs'],
            ['code' => 'CS', 'name' => 'College of Science', 'description' => 'Science programs'],
            ['code' => 'CTHTM', 'name' => 'College of Tourism, Hospitality and Transportation Management', 'description' => 'Tourism, Hospitality and Transportation Management programs'],
        ];
        
        DB::table('departments')->insert($departments);
        
        // 2. Insert Admin User (password: admin123)
        DB::table('users')->insert([
            'user_id' => 'ADMIN-001',
            'name' => 'System Administrator',
            'email' => 'admin@edurate.edu',
            'password' => Hash::make('admin123'),
            'role' => 'admin',
            'is_active' => true,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        
        // 3. Insert Faculty Users (password: faculty123)
        $faculty = [
            ['user_id' => 'FAC-001', 'name' => 'Dr. Rogelio Reyes', 'email' => 'r.reyes@pup.edu.ph', 'department_code' => 'CCIS'],
            ['user_id' => 'FAC-002', 'name' => 'Dr. Sarah Santos', 'email' => 's.santos@pup.edu.ph', 'department_code' => 'CCIS'],
            ['user_id' => 'FAC-003', 'name' => 'Prof. Ricardo Dalisay', 'email' => 'r.dalisay@pup.edu.ph', 'department_code' => 'CCIS'],
            ['user_id' => 'FAC-004', 'name' => 'Prof. Maria Santos', 'email' => 'm.santos@pup.edu.ph', 'department_code' => 'CBA'],
            ['user_id' => 'FAC-005', 'name' => 'Prof. Juan Dela Cruz', 'email' => 'j.delacruz@pup.edu.ph', 'department_code' => 'CE'],
        ];
        
        foreach ($faculty as $fac) {
            DB::table('users')->insert([
                'user_id' => $fac['user_id'],
                'name' => $fac['name'],
                'email' => $fac['email'],
                'password' => Hash::make('faculty123'),
                'role' => 'faculty',
                'department_code' => $fac['department_code'],
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 4. Insert Student Users (password: student123)
        $students = [
            ['user_id' => '2023-00123-MN-0', 'name' => 'Juan Dela Cruz', 'email' => 'juan@isko.pup.edu.ph', 'department_code' => 'CCIS', 'section' => 'BSIT 3-1'],
            ['user_id' => '2023-00124-MN-0', 'name' => 'Maria Santos', 'email' => 'maria@isko.pup.edu.ph', 'department_code' => 'CCIS', 'section' => 'BSIT 3-1'],
            ['user_id' => '2023-00125-MN-0', 'name' => 'Jose Rizal', 'email' => 'jose@isko.pup.edu.ph', 'department_code' => 'CCIS', 'section' => 'BSIT 3-2'],
            ['user_id' => '2023-00126-MN-0', 'name' => 'Andres Bonifacio', 'email' => 'andres@isko.pup.edu.ph', 'department_code' => 'CBA', 'section' => 'BSBA 2-1'],
            ['user_id' => '2023-00127-MN-0', 'name' => 'Gabriela Silang', 'email' => 'gabriela@isko.pup.edu.ph', 'department_code' => 'CE', 'section' => 'BSCE 4-1'],
        ];
        
        foreach ($students as $student) {
            DB::table('users')->insert([
                'user_id' => $student['user_id'],
                'name' => $student['name'],
                'email' => $student['email'],
                'password' => Hash::make('student123'),
                'role' => 'student',
                'department_code' => $student['department_code'],
                'section' => $student['section'],
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        
        // 5. Insert Subjects
        $subjects = [
            // CCIS Subjects
            ['code' => 'COMP 20133', 'name' => 'Application Development', 'department_code' => 'CCIS', 'units' => 3],
            ['code' => 'INTE 30023', 'name' => 'Integrative Programming', 'department_code' => 'CCIS', 'units' => 3],
            ['code' => 'COMP 20183', 'name' => 'Platform Technologies', 'department_code' => 'CCIS', 'units' => 3],
            ['code' => 'COMP 20213', 'name' => 'Web Development', 'department_code' => 'CCIS', 'units' => 3],
            ['code' => 'INTE 30033', 'name' => 'Information Management', 'department_code' => 'CCIS', 'units' => 3],
            // CBA Subjects
            ['code' => 'MKTG 101', 'name' => 'Principles of Marketing', 'department_code' => 'CBA', 'units' => 3],
            ['code' => 'MGMT 101', 'name' => 'Principles of Management', 'department_code' => 'CBA', 'units' => 3],
            // CE Subjects
            ['code' => 'CE 101', 'name' => 'Engineering Mechanics', 'department_code' => 'CE', 'units' => 4],
            ['code' => 'CE 102', 'name' => 'Structural Analysis', 'department_code' => 'CE', 'units' => 4],
        ];
        
        DB::table('subjects')->insert($subjects);
        
        // 6. Insert Sections
        $sections = [
            ['name' => 'BSIT 3-1', 'department_code' => 'CCIS', 'year_level' => 3, 'schedule_type' => 'day'],
            ['name' => 'BSIT 3-2', 'department_code' => 'CCIS', 'year_level' => 3, 'schedule_type' => 'day'],
            ['name' => 'BSIT 4-1', 'department_code' => 'CCIS', 'year_level' => 4, 'schedule_type' => 'day'],
            ['name' => 'BSIT 4-2', 'department_code' => 'CCIS', 'year_level' => 4, 'schedule_type' => 'day'],
            ['name' => 'BSIT 3-1N', 'department_code' => 'CCIS', 'year_level' => 3, 'schedule_type' => 'night'],
            ['name' => 'BSBA 2-1', 'department_code' => 'CBA', 'year_level' => 2, 'schedule_type' => 'day'],
            ['name' => 'BSBA 3-1', 'department_code' => 'CBA', 'year_level' => 3, 'schedule_type' => 'day'],
            ['name' => 'BSCE 4-1', 'department_code' => 'CE', 'year_level' => 4, 'schedule_type' => 'day'],
            ['name' => 'BSCE 3-1', 'department_code' => 'CE', 'year_level' => 3, 'schedule_type' => 'day'],
        ];
        
        DB::table('sections')->insert($sections);
        
        // 7. Insert Evaluation Criteria
        $criteria = [
            // Instructional Competence
            ['question' => 'Demonstrates mastery of the subject.', 'category' => 'Instructional Competence', 'order' => 1],
            ['question' => 'Explains concepts clearly and makes them easy to understand.', 'category' => 'Instructional Competence', 'order' => 2],
            ['question' => 'Used relevant examples or real-world applications to illustrate lessons.', 'category' => 'Instructional Competence', 'order' => 3],
            ['question' => 'Encourages student participation and questions during discussion.', 'category' => 'Instructional Competence', 'order' => 4],
            ['question' => 'Uses effective teaching aids (PPT, visual aids, online resources) to enhance learning.', 'category' => 'Instructional Competence', 'order' => 5],
            
            // Classroom Management
            ['question' => 'Starts and ends classes on time.', 'category' => 'Classroom Management', 'order' => 1],
            ['question' => 'Maintains an orderly and conductive learning environment.', 'category' => 'Classroom Management', 'order' => 2],
            ['question' => 'Manages class time effectively (not spending too much time on irrelevant topics).', 'category' => 'Classroom Management', 'order' => 3],
            ['question' => 'Is approachable and available for consultation during specified hours.', 'category' => 'Classroom Management', 'order' => 4],
            ['question' => 'Implements class policies fairly and consistently.', 'category' => 'Classroom Management', 'order' => 5],
            
            // Assessment and Feedback
            ['question' => 'Provides clear guidelines and criteria for assignments and projects.', 'category' => 'Assessment and Feedback', 'order' => 1],
            ['question' => 'Returns quizzes, exams, and projects in a timely manner.', 'category' => 'Assessment and Feedback', 'order' => 2],
            ['question' => 'Gives constructive feedback to help improve student performance.', 'category' => 'Assessment and Feedback', 'order' => 3],
            ['question' => 'Computes grades fairly based on the presented syllabus.', 'category' => 'Assessment and Feedback', 'order' => 4],
            ['question' => 'Assessments align with the learning objectives and content discussed.', 'category' => 'Assessment and Feedback', 'order' => 5],
            
            // Professionalism
            ['question' => 'Shows respect for students regardless of gender, religion, or background.', 'category' => 'Professionalism', 'order' => 1],
            ['question' => 'Demonstrates enthusiasm in teaching the subject.', 'category' => 'Professionalism', 'order' => 2],
            ['question' => 'Accepts constructive criticism and suggestions from students.', 'category' => 'Professionalism', 'order' => 3],
            ['question' => 'Adheres to school policies regarding attendance and syllabus implementation.', 'category' => 'Professionalism', 'order' => 4],
            ['question' => 'Maintains professional appearance and demeanor.', 'category' => 'Professionalism', 'order' => 5],
        ];
        
        DB::table('evaluation_criteria')->insert($criteria);
        
        // 8. Insert System Settings
        $settings = [
            ['key' => 'evaluation_status', 'value' => 'open', 'type' => 'string', 'description' => 'Evaluation system status (open/closed)'],
            ['key' => 'current_academic_year', 'value' => '2025-2026', 'type' => 'string', 'description' => 'Current academic year'],
            ['key' => 'current_semester', 'value' => '1st', 'type' => 'string', 'description' => 'Current semester'],
            ['key' => 'evaluation_start_date', 'value' => '2025-09-01', 'type' => 'date', 'description' => 'Evaluation period start date'],
            ['key' => 'evaluation_end_date', 'value' => '2025-12-15', 'type' => 'date', 'description' => 'Evaluation period end date'],
            ['key' => 'minimum_responses', 'value' => '10', 'type' => 'integer', 'description' => 'Minimum responses for valid evaluation'],
            ['key' => 'rating_scale', 'value' => '5', 'type' => 'integer', 'description' => 'Rating scale (1-5)'],
            ['key' => 'system_name', 'value' => 'EduRate Faculty Evaluation System', 'type' => 'string', 'description' => 'System name'],
            ['key' => 'institution_name', 'value' => 'Polytechnic University of the Philippines', 'type' => 'string', 'description' => 'Institution name'],
            ['key' => 'admin_email', 'value' => 'admin@edurate.edu', 'type' => 'string', 'description' => 'Administrator email'],
        ];
        
        DB::table('system_settings')->insert($settings);
        
        $this->command->info('✅ Database seeded successfully!');
        $this->command->info('👨‍💼 Admin Login: admin@edurate.edu / admin123');
        $this->command->info('👨‍🏫 Faculty Login: Use any faculty email / faculty123');
        $this->command->info('🎓 Student Login: Use any student email / student123');
    }
}