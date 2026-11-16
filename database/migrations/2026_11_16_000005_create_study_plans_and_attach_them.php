<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_plans', function (Blueprint $table) {
            $table->id();
            $table->string('code', 40)->unique();
            $table->string('name', 150);
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::table('students', function (Blueprint $table) {
            $table->foreignId('study_plan_id')->nullable()->constrained()->nullOnDelete();
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->foreignId('study_plan_id')->nullable()->constrained()->nullOnDelete();
        });

        $programs = DB::table('students')
            ->whereNotNull('program')
            ->where('program', '!=', '')
            ->distinct()
            ->pluck('program');

        foreach ($programs as $program) {
            $code = substr(Str::slug($program), 0, 30) ?: 'plan';

            if (DB::table('study_plans')->where('code', $code)->exists()) {
                $code .= '-'.substr(sha1($program), 0, 8);
            }

            $now = now();
            $planId = DB::table('study_plans')->insertGetId([
                'code' => $code,
                'name' => $program,
                'is_active' => true,
                'created_at' => $now,
                'updated_at' => $now,
            ]);

            DB::table('students')->where('program', $program)->update(['study_plan_id' => $planId]);
        }
    }

    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_plan_id');
        });

        Schema::table('students', function (Blueprint $table) {
            $table->dropConstrainedForeignId('study_plan_id');
        });

        Schema::dropIfExists('study_plans');
    }
};
