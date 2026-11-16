<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('syllabi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_plan_id')->constrained()->restrictOnDelete();
            $table->string('title', 150);
            $table->string('original_filename');
            $table->string('path');
            $table->boolean('is_published')->default(false);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->index(['study_plan_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('syllabi');
    }
};
