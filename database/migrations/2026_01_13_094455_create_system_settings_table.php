<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 50)->default('string');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('key');
        });
    }

    public function down()
    {
        Schema::dropIfExists('system_settings');
    }
};