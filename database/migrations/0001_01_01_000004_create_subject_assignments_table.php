<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('subject_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->foreignId('faculty_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('section_id')->constrained()->onDelete('cascade');
            $table->string('academic_year', 20)->comment('Format: 2025-2026');
            $table->enum('semester', ['1st', '2nd', 'summer'])->default('1st');
            $table->string('room', 50)->nullable();
            $table->string('schedule', 100)->nullable();
            $table->timestamps();
            
            $table->unique(
                ['subject_id', 'faculty_id', 'section_id', 'academic_year', 'semester'], 
                'sub_assign_unique'
            );

            $table->index('faculty_id');
            $table->index('section_id');
            $table->index(['academic_year', 'semester']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('subject_assignments');
    }
};