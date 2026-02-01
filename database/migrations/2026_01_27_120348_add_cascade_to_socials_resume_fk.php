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
      Schema::table('socials', function (Blueprint $table) {
        $table->dropForeign(['resume_id']);

        $table->foreign('resume_id')
              ->references('id')
              ->on('resumes')
              ->onDelete('cascade');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('socials', function (Blueprint $table) {
        $table->dropForeign(['resume_id']);

        $table->foreign('resume_id')
              ->references('id')
              ->on('resumes');
    });
    }
};
