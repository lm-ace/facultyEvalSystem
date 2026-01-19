<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
{
    Schema::table('students', function (Blueprint $table) {

        $table->foreignId('section_id')
              ->nullable()
              ->constrained('class_sections') // Links to class_sections table
              ->onDelete('set null');
    });
}

public function down()
{
    Schema::table('students', function (Blueprint $table) {
        $table->dropForeign(['section_id']);
        $table->dropColumn('section_id');
    });
}
};