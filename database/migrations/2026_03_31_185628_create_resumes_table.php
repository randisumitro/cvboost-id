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
        Schema::create('resumes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('session_id')->nullable();
            $table->foreignId('template_id')->constrained();
            $table->string('title')->default('Untitled CV');
            $table->json('personal_data');
            $table->json('experiences')->nullable();
            $table->json('educations')->nullable();
            $table->json('skills')->nullable();
            $table->string('primary_color', 7)->default('#3490dc');
            $table->string('font_family', 50)->default('Poppins');
            $table->boolean('is_completed')->default(false);
            $table->integer('ats_score')->nullable();
            $table->json('ats_feedback')->nullable();
            $table->integer('download_count')->default(0);
            $table->timestamp('last_downloaded_at')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('session_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumes');
    }
};
