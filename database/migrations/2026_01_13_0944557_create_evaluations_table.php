<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->string('academic_year', 20);
            $table->enum('semester', ['1st', '2nd', 'summer'])->default('1st');
            $table->json('ratings')->comment('Store {criterion_id: rating}');
            $table->decimal('average_rating', 3, 2)->default(0.00);
            $table->text('comments')->nullable();
            $table->boolean('is_anonymous')->default(true);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
            
            // 1. UNIQUE CONSTRAINT (May maikling pangalan para hindi mag-error)
            $table->unique(['student_id', 'faculty_id', 'subject_id', 'academic_year', 'semester'], 'eval_uniq_ref');

            // 2. SINGLE INDEXES
            $table->index('faculty_id');
            $table->index('student_id');
            $table->index('subject_id');
            $table->index('completed_at');

            // 3. COMPOSITE INDEXES (Inayos ang mga pangalan para walang duplicate)
            $table->index(['academic_year', 'semester'], 'eval_acad_sem_idx');
            $table->index(['faculty_id', 'completed_at'], 'eval_fac_comp_idx');
            $table->index(['academic_year', 'semester', 'completed_at'], 'eval_period_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('evaluations');
    }
};