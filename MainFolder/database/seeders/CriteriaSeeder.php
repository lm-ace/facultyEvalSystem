<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CriteriaSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();

        $userId = 1;
        $userExists = DB::table('users')->where('id', $userId)->exists();

        if (!$userExists) {
            DB::table('users')->insert([
                'id'       => $userId,
                'name'     => 'Super Admin User',
                'email'    => 'superadmin@test.com',
                'password' => Hash::make('password'),
                'created_at' => $now,
                'updated_at' => $now,
            ]);
            $this->command->info("Created dummy User with ID: $userId");
        }

        $adminId = 1;
        $adminExists = DB::table('admins')->where('id', $adminId)->exists();

        if (!$adminExists) {
            DB::table('admins')->insert([
                'id'         => $adminId,
                'user_id'    => $userId,
                'first_name' => 'Super',
                'last_name'  => 'Admin',
                'role_label' => 'System Administrator',
                'created_at' => $now,
            ]);
            $this->command->info("Created dummy Admin with ID: $adminId");
        }

        $templateId = DB::table('criteria_templates')->insertGetId([
            'name'        => 'Standard Faculty Evaluation',
            'description' => 'Default evaluation form for 2026 Academic Year',
            'version'     => '1.0',
            'created_by'  => $adminId,
            'active'      => true,
            'created_at'  => $now,
        ]);

        $this->command->info("Created Template ID: $templateId");

        $sectionsData = [
            'Instructional Competence' => [
                'Demonstrates mastery of the subject.',
                'Explains concepts clearly and makes them easy to understand.',
                'Used relevant examples or real-world applications to illustrate lessons.',
                'Encourages student participation and questions during discussion.',
                'Uses effective teaching aids (PPT, visual aids, online resources) to enhance learning.',
            ],
            'Classroom Management' => [
                'Starts and ends classes on time.',
                'Maintains an orderly and conductive learning environment.',
                'Manages class time effectively (not spending too much time on irrelevant topics).',
                'Is approachable and available for consultation during specified hours.',
                'Implements class policies fairly and consistently.',
            ],
            'Assessment and Feedback' => [
                'Provides clear guidelines and criteria for assignments and projects.',
                'Returns quizzes, exams, and projects in a timely manner.',
                'Gives constructive feedback to help improve student performance.',
                'Computes grades fairly based on the presented syllabus.',
                'Assessments align with the learning objectives and content discussed.',
            ],
            'Professionalism' => [
                'Shows respect for students regardless of gender, religion, or background.',
                'Demonstrates enthusiasm in teaching the subject.',
                'Accepts constructive criticism and suggestions from students.',
                'Adheres to school policies regarding attendance and syllabus implementation.',
                'Maintains professional appearance and demeanor.',
            ],
        ];

        $positionCounter = 1;

        foreach ($sectionsData as $sectionName => $questions) {
            
            // Create Section
            $sectionId = DB::table('criteria_sections')->insertGetId([
                'template_id'    => $templateId,
                'section_number' => $positionCounter,
                'section_name'   => $sectionName,
                'position'       => $positionCounter,
            ]);

            // Create Items
            foreach ($questions as $index => $questionText) {
                DB::table('criteria_items')->insert([
                    'section_id'    => $sectionId,
                    'item_number'   => $index + 1,
                    'question_text' => $questionText,
                    'max_score'     => 5,
                    'position'      => $index + 1,
                ]);
            }
            $positionCounter++;
        }
    }
}