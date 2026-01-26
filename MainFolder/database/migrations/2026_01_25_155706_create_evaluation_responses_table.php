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
       Schema::create('evaluation_responses', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('evaluation_id')->constrained('evaluations')->onDelete('cascade');
        
            $table->foreignId('criteria_item_id')->constrained('criteria_items')->onDelete('cascade');
            
            $table->tinyInteger('score');
            $table->timestamp('created_at')->useCurrent(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evaluation_responses');
    }
};
