<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
       Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('role', 16);
        $table->string('username', 100)->nullable()->unique();
        $table->string('email', 255)->nullable()->unique();
        $table->string('password_hash', 255); 
        $table->tinyInteger('is_active')->default(1);
        $table->timestamp('last_login')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
