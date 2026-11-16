<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->foreignId('course_id')->nullable()->after('study_plan_id')->constrained()->restrictOnDelete();
            $table->foreignId('uploaded_by_id')->nullable()->after('course_id')->constrained('users')->nullOnDelete();
            $table->index(['course_id', 'is_published']);
        });
    }

    public function down(): void
    {
        Schema::table('syllabi', function (Blueprint $table) {
            $table->dropIndex(['course_id', 'is_published']);
            $table->dropConstrainedForeignId('uploaded_by_id');
            $table->dropConstrainedForeignId('course_id');
        });
    }
};
