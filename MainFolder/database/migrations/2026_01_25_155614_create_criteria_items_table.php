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
       Schema::create('criteria_items', function (Blueprint $table) {
        $table->id();
        $table->foreignId('section_id')->constrained('criteria_sections')->onDelete('cascade');
        $table->integer('item_number');
        $table->text('question_text');
        $table->tinyInteger('max_score')->default(5);
        $table->integer('position');
        // No timestamps in PDF
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('criteria_items');
    }
};
