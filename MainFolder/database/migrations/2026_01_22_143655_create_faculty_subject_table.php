<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
      Schema::create('faculty_subject', function (Blueprint $table) {
            $table->id();
            
            // Link to Faculty Table
            $table->foreignId('faculty_id')
                  ->constrained('faculties') // Points to 'faculties' table
                  ->onDelete('cascade');     // If faculty is deleted, remove this link
            
            // Link to Subject Table
            $table->foreignId('subject_id')
                  ->constrained('subjects')  // Points to 'subjects' table
                  ->onDelete('cascade');     // If subject is deleted, remove this link
            
            $table->timestamps();

            // Prevent duplicate assignments (e.g., assigning Subject A to Faculty X twice)
            $table->unique(['faculty_id', 'subject_id']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('faculty_subject');
    }
};
