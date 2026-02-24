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
        Schema::create('job_openings', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('department', 100);
            $table->string('location', 255);
            $table->string('employment_type', 30);
            $table->string('status', 20)->default('open');
            $table->string('application_email', 255)->default('hrd_buscosugarmill@yahoo.com');
            $table->date('posted_at')->nullable();
            $table->date('deadline_at')->nullable();
            $table->text('summary')->nullable();
            $table->longText('description');
            $table->longText('qualifications')->nullable();
            $table->longText('responsibilities')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_openings');
    }
};
