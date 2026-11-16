<?php

namespace Database\Seeders;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseSchedule;
use App\Models\Grade;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@educontrol.test'],
            ['name' => 'Administración EduControl', 'password' => Hash::make('password'), 'role' => User::ROLE_ADMIN],
        );
        $teacher = User::updateOrCreate(
            ['email' => 'docente@educontrol.test'],
            ['name' => 'Docente Demo', 'password' => Hash::make('password'), 'role' => User::ROLE_TEACHER],
        );

        $systemsPlan = StudyPlan::updateOrCreate(
            ['code' => 'desarrollo-de-sistemas'],
            ['name' => 'Desarrollo de Sistemas', 'is_active' => true],
        );
        $administrationPlan = StudyPlan::updateOrCreate(
            ['code' => 'administracion'],
            ['name' => 'Administración', 'is_active' => true],
        );

        $students = collect([
            ['student_code' => 'EC-2026-001', 'document_number' => '71000001', 'first_name' => 'Camila', 'last_name' => 'Rojas', 'email' => 'camila@educontrol.test', 'program' => 'Desarrollo de Sistemas'],
            ['student_code' => 'EC-2026-002', 'document_number' => '71000002', 'first_name' => 'Mateo', 'last_name' => 'Vargas', 'email' => 'mateo@educontrol.test', 'program' => 'Desarrollo de Sistemas'],
            ['student_code' => 'EC-2026-003', 'document_number' => '71000003', 'first_name' => 'Lucía', 'last_name' => 'Paredes', 'email' => 'lucia@educontrol.test', 'program' => 'Administración'],
        ])->map(function (array $data) use ($systemsPlan, $administrationPlan) {
            $plan = $data['program'] === $systemsPlan->name ? $systemsPlan : $administrationPlan;

            return Student::updateOrCreate(
                ['student_code' => $data['student_code']],
                [...$data, 'study_plan_id' => $plan->id],
            );
        });

        $course = Course::updateOrCreate(
            ['code' => 'PRG-101'],
            ['name' => 'Fundamentos de programación', 'section' => 'A', 'term' => '2026-II', 'schedule' => 'Lun y mié · 08:00', 'teacher_id' => $teacher->id, 'study_plan_id' => $systemsPlan->id],
        );
        $databaseCourse = Course::updateOrCreate(
            ['code' => 'BD-201'],
            ['name' => 'Bases de datos', 'section' => 'B', 'term' => '2026-II', 'schedule' => 'Mar y jue · 10:00', 'teacher_id' => $teacher->id, 'study_plan_id' => $systemsPlan->id],
        );

        foreach ([
            [$course, 1, '08:00', '09:30', 'Aula 101'],
            [$course, 3, '08:00', '09:30', 'Aula 101'],
            [$databaseCourse, 2, '10:00', '11:30', 'Aula 202'],
            [$databaseCourse, 4, '10:00', '11:30', 'Aula 202'],
        ] as [$scheduledCourse, $day, $startsAt, $endsAt, $classroom]) {
            CourseSchedule::updateOrCreate(
                ['course_id' => $scheduledCourse->id, 'day_of_week' => $day, 'starts_at' => $startsAt],
                ['ends_at' => $endsAt, 'classroom' => $classroom],
            );
        }

        $assessment = Assessment::updateOrCreate(
            ['course_id' => $course->id, 'title' => 'Evaluación parcial'],
            ['assessment_date' => today()->toDateString()],
        );

        foreach ($students as $index => $student) {
            Attendance::updateOrCreate(
                ['student_id' => $student->id, 'course_id' => $course->id, 'attendance_date' => today()->toDateString()],
                ['status' => $index === 1 ? 'tardanza' : 'presente', 'marked_by' => $teacher->id],
            );

            if ($student->study_plan_id === $systemsPlan->id) {
                Grade::updateOrCreate(
                    ['assessment_id' => $assessment->id, 'student_id' => $student->id],
                    ['score' => $index === 1 ? 16 : 18],
                );
            }
        }
    }
}
