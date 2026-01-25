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
       Schema::create('review_periods', function (Blueprint $table) {
        $table->id();
        $table->string('name', 100);
        $table->string('academic_year', 20);
        $table->string('semester', 20);
        $table->date('start_date');
        $table->date('end_date');
        $table->tinyInteger('is_open')->default(0);
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('review_periods');
    }
};
