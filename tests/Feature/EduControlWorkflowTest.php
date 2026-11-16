<?php

namespace Tests\Feature;

use App\Models\Assessment;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use App\Models\StudyPlan;
use App\Models\Syllabus;
use App\Models\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EduControlWorkflowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_sent_to_the_login_page(): void
    {
        $this->get('/')->assertRedirect(route('login'));
        $this->get(route('login'))->assertOk()->assertSee('EduControl');
    }

    public function test_docente_url_redirects_to_attendances(): void
    {
        $this->get('/docente')->assertRedirect(route('attendances.index'));
    }

    public function test_login_redirects_each_role_to_its_workspace(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->post(route('login.submit'), [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->post(route('logout'));
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->post(route('login.submit'), [
            'email' => $teacher->email,
            'password' => 'password',
        ])->assertRedirect(route('attendances.index'));
    }

    public function test_admin_can_create_students_and_courses(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $studyPlan = StudyPlan::create([
            'code' => 'desarrollo-sistemas',
            'name' => 'Desarrollo de Sistemas',
            'is_active' => true,
        ]);
        $this->actingAs($admin);

        $this->post(route('students.store'), [
            'student_code' => 'EC-001',
            'document_number' => '12345678',
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'email' => 'ana@example.test',
            'study_plan_id' => $studyPlan->id,
            'is_active' => '1',
        ])->assertRedirect(route('students.index'));

        $this->post(route('courses.store'), [
            'code' => 'PRG-101',
            'name' => 'Programación',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
            'study_plan_id' => $studyPlan->id,
        ])->assertRedirect(route('courses.index'));

        $this->assertDatabaseHas('students', ['student_code' => 'EC-001']);
        $this->assertDatabaseHas('courses', ['code' => 'PRG-101', 'teacher_id' => $teacher->id]);

        $student = Student::where('student_code', 'EC-001')->firstOrFail();
        $this->put(route('students.update', $student), [
            'student_code' => 'EC-001',
            'document_number' => '12345678',
            'first_name' => 'Ana María',
            'last_name' => 'Pérez',
            'email' => 'ana@example.test',
            'study_plan_id' => $studyPlan->id,
            'is_active' => '1',
        ])->assertRedirect(route('students.index'));
        $this->assertDatabaseHas('students', ['id' => $student->id, 'first_name' => 'Ana María']);

        $course = Course::where('code', 'PRG-101')->firstOrFail();
        $this->put(route('courses.update', $course), [
            'code' => 'PRG-101',
            'name' => 'Programación I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
            'study_plan_id' => $studyPlan->id,
        ])->assertRedirect(route('courses.index'));
        $this->assertDatabaseHas('courses', ['id' => $course->id, 'name' => 'Programación I']);

        $this->delete(route('students.destroy', $student))->assertRedirect(route('students.index'));
        $this->delete(route('courses.destroy', $course))->assertRedirect(route('courses.index'));
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
        $this->assertDatabaseMissing('courses', ['id' => $course->id]);
    }

    public function test_teacher_can_record_attendance_only_for_assigned_courses(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::create([
            'code' => 'PRG-101',
            'name' => 'Programación',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        $otherCourse = Course::create([
            'code' => 'BD-201',
            'name' => 'Bases de datos',
            'section' => 'B',
            'term' => '2026-II',
            'teacher_id' => $otherTeacher->id,
        ]);
        $student = Student::create([
            'student_code' => 'EC-001',
            'document_number' => '12345678',
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'program' => 'Desarrollo de Sistemas',
            'is_active' => true,
        ]);
        $this->actingAs($teacher);

        $payload = [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'presente',
            'notes' => '',
        ];
        $this->post(route('attendances.store'), $payload)->assertRedirect(route('attendances.index'));
        $this->assertDatabaseHas('attendances', ['student_id' => $student->id, 'course_id' => $course->id]);

        $attendance = Attendance::where('student_id', $student->id)->firstOrFail();
        $this->put(route('attendances.update', $attendance), [...$payload, 'status' => 'tardanza'])
            ->assertRedirect(route('attendances.index'));
        $this->assertDatabaseHas('attendances', ['id' => $attendance->id, 'status' => 'tardanza']);
        $this->post(route('attendances.store'), [...$payload, 'course_id' => $otherCourse->id])->assertForbidden();
        $this->delete(route('attendances.destroy', $attendance))->assertRedirect(route('attendances.index'));
        $this->assertDatabaseMissing('attendances', ['id' => $attendance->id]);
    }

    public function test_attendance_cannot_be_duplicated_for_a_student_course_and_date(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::create([
            'code' => 'PRG-101',
            'name' => 'Programación',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        $student = Student::create([
            'student_code' => 'EC-001',
            'document_number' => '12345678',
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'program' => 'Desarrollo de Sistemas',
            'is_active' => true,
        ]);
        Attendance::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'presente',
            'marked_by' => $teacher->id,
        ]);
        $this->actingAs($teacher);

        $this->from(route('attendances.create'))->post(route('attendances.store'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'ausente',
        ])->assertSessionHasErrors('student_id');

        $this->assertDatabaseCount('attendances', 1);
    }

    public function test_admin_dashboard_displays_academic_summary(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $student = Student::create([
            'student_code' => 'EC-001',
            'document_number' => '12345678',
            'first_name' => 'Ana',
            'last_name' => 'Pérez',
            'program' => 'Desarrollo de Sistemas',
            'is_active' => true,
        ]);
        $course = Course::create([
            'code' => 'PRG-101',
            'name' => 'Programación',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        Attendance::create([
            'student_id' => $student->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'presente',
            'marked_by' => $teacher->id,
        ]);

        $this->actingAs($admin)
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('Panel general')
            ->assertSee('Ana Pérez')
            ->assertSee('Programación')
            ->assertSee('100');
    }

    public function test_attendance_filters_can_be_combined(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::create([
            'code' => 'PRG-101',
            'name' => 'Programación',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        $students = collect([
            ['student_code' => 'EC-001', 'document_number' => '12345678', 'first_name' => 'Camila', 'last_name' => 'Rojas'],
            ['student_code' => 'EC-002', 'document_number' => '12345679', 'first_name' => 'Mateo', 'last_name' => 'Vargas'],
        ])->map(fn (array $data) => Student::create([
            ...$data,
            'program' => 'Desarrollo de Sistemas',
            'is_active' => true,
        ]));
        Attendance::create([
            'student_id' => $students[0]->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'presente',
            'marked_by' => $teacher->id,
        ]);
        Attendance::create([
            'student_id' => $students[1]->id,
            'course_id' => $course->id,
            'attendance_date' => today()->toDateString(),
            'status' => 'ausente',
            'marked_by' => $teacher->id,
        ]);

        $this->actingAs($admin)
            ->get(route('attendances.index', [
                'q' => 'Mateo',
                'course_id' => $course->id,
                'status' => 'ausente',
                'date' => today()->toDateString(),
            ]))
            ->assertOk()
            ->assertSee('Mateo Vargas')
            ->assertDontSee('Camila Rojas');
    }

    public function test_teacher_is_redirected_from_administrator_pages(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_TEACHER]));

        $this->get(route('students.index'))->assertRedirect(route('attendances.index'));
        $this->get(route('courses.index'))->assertRedirect(route('attendances.index'));
        $this->get(route('dashboard'))
            ->assertRedirect(route('attendances.index'))
            ->assertSessionHas('error');
    }

    public function test_attendance_area_explains_admin_and_teacher_responsibilities(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin)
            ->get(route('attendances.index'))
            ->assertOk()
            ->assertSee('¿Para qué sirve Asistencias en Administración?')
            ->assertSee('consulta registros de todos los cursos')
            ->assertSee('La toma habitual de asistencia corresponde al docente')
            ->assertSee('Registros institucionales');

        $this->post(route('logout'));
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $this->actingAs($teacher)
            ->get(route('attendances.index'))
            ->assertOk()
            ->assertSee('Tu función docente en Asistencias')
            ->assertSee('pasar lista en cada clase')
            ->assertSee('Asistencia de mis cursos');
    }

    public function test_administrator_navigation_explains_academic_workflow(): void
    {
        $this->actingAs(User::factory()->create(['role' => User::ROLE_ADMIN]))
            ->get(route('dashboard'))
            ->assertOk()
            ->assertSee('INICIO')
            ->assertSee('GESTIÓN ACADÉMICA')
            ->assertSee('SEGUIMIENTO')
            ->assertSee('Registra estudiantes')
            ->assertSee('Organiza cursos')
            ->assertSee('Supervisa asistencia');

        $this->get(route('students.index'))
            ->assertOk()
            ->assertSee('Los estudiantes activos estarán disponibles para tomar asistencia.');
        $this->get(route('courses.index'))
            ->assertOk()
            ->assertSee('Los docentes verán únicamente los cursos que tienen asignados.');
    }

    public function test_admin_can_create_and_remove_unused_study_plans(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $this->actingAs($admin)
            ->get(route('study-plans.create'))
            ->assertOk()
            ->assertSee('Crear plan de estudio');

        $this->post(route('study-plans.store'), [
            'code' => 'diseno-grafico',
            'name' => 'Diseño Gráfico',
            'description' => 'Plan de diseño',
            'is_active' => '1',
        ])
            ->assertRedirect(route('study-plans.index'));

        $studyPlan = StudyPlan::where('code', 'diseno-grafico')->firstOrFail();
        $this->assertDatabaseHas('study_plans', ['id' => $studyPlan->id, 'name' => 'Diseño Gráfico']);
        $this->delete(route('study-plans.destroy', $studyPlan))->assertRedirect(route('study-plans.index'));
        $this->assertDatabaseMissing('study_plans', ['id' => $studyPlan->id]);
    }

    public function test_admin_can_upload_and_publish_a_syllabus_only_to_assigned_plan_teachers(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $studyPlan = StudyPlan::create(['code' => 'sistemas', 'name' => 'Sistemas', 'is_active' => true]);
        $otherPlan = StudyPlan::create(['code' => 'contabilidad', 'name' => 'Contabilidad', 'is_active' => true]);
        Course::create([
            'code' => 'SYS-101',
            'name' => 'Sistemas I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
            'study_plan_id' => $studyPlan->id,
        ]);
        Course::create([
            'code' => 'CON-101',
            'name' => 'Contabilidad I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $otherTeacher->id,
            'study_plan_id' => $otherPlan->id,
        ]);

        $this->actingAs($admin)
            ->get(route('syllabi.create'))
            ->assertOk()
            ->assertSee('PDF de hasta 2 MB');
        $this->actingAs($admin)
            ->post(route('syllabi.store'), [
                'study_plan_id' => $studyPlan->id,
                'title' => 'Sílabo 2026-II',
                'file' => UploadedFile::fake()->createWithContent('silabo.pdf', "%PDF-1.4\nEduControl\n"),
                'is_published' => '1',
            ])
            ->assertRedirect(route('syllabi.index'));

        $syllabus = Syllabus::firstOrFail();
        $this->assertTrue($syllabus->is_published);
        Storage::disk('local')->assertExists($syllabus->path);
        $this->actingAs($teacher)
            ->get(route('syllabi.index'))
            ->assertOk()
            ->assertSee('Sílabo 2026-II');
        $this->get(route('syllabi.download', $syllabus))->assertDownload('silabo.pdf');

        $this->actingAs($otherTeacher)
            ->get(route('syllabi.download', $syllabus))
            ->assertForbidden();

        $this->actingAs($admin)
            ->patch(route('syllabi.publication', $syllabus), ['is_published' => '0'])
            ->assertRedirect(route('syllabi.index'));
        $this->actingAs($teacher)
            ->get(route('syllabi.index'))
            ->assertOk()
            ->assertDontSee('Sílabo 2026-II');
        $this->get(route('syllabi.download', $syllabus))->assertForbidden();
    }

    public function test_teacher_can_submit_a_course_syllabus_for_admin_approval(): void
    {
        Storage::fake('local');
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $studyPlan = StudyPlan::create(['code' => 'sistemas', 'name' => 'Sistemas', 'is_active' => true]);
        $course = Course::create([
            'code' => 'SYS-101',
            'name' => 'Sistemas I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
            'study_plan_id' => $studyPlan->id,
        ]);
        Course::create([
            'code' => 'SYS-102',
            'name' => 'Redes',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $otherTeacher->id,
            'study_plan_id' => $studyPlan->id,
        ]);

        $this->actingAs($teacher)
            ->get(route('syllabi.create'))
            ->assertOk()
            ->assertSee('Sistemas I')
            ->assertDontSee('Redes');
        $this->post(route('syllabi.store'), [
            'course_id' => Course::where('code', 'SYS-102')->value('id'),
            'title' => 'Sílabo ajeno',
            'file' => UploadedFile::fake()->createWithContent('ajeno.pdf', "%PDF-1.4\nEduControl\n"),
        ])->assertForbidden();
        $this->post(route('syllabi.store'), [
            'course_id' => $course->id,
            'title' => 'Sílabo de Sistemas I',
            'file' => UploadedFile::fake()->createWithContent('sistemas.pdf', "%PDF-1.4\nEduControl\n"),
            'is_published' => '1',
        ])->assertRedirect(route('syllabi.index'))
            ->assertSessionHas('success', 'Sílabo cargado y enviado al administrador para su aprobación.');

        $syllabus = Syllabus::firstOrFail();
        $this->assertDatabaseHas('syllabi', [
            'id' => $syllabus->id,
            'study_plan_id' => $studyPlan->id,
            'course_id' => $course->id,
            'uploaded_by_id' => $teacher->id,
            'is_published' => false,
        ]);
        Storage::disk('local')->assertExists($syllabus->path);
        $this->get(route('syllabi.download', $syllabus))->assertDownload('sistemas.pdf');
        $this->get(route('syllabi.index'))
            ->assertOk()
            ->assertSee('Pendiente de aprobación');
        $this->actingAs($otherTeacher)
            ->get(route('syllabi.download', $syllabus))
            ->assertForbidden();
        $this->actingAs($admin)
            ->delete(route('courses.destroy', $course))
            ->assertRedirect(route('courses.index'))
            ->assertSessionHas('error', 'Elimina primero los sílabos asociados antes de eliminar este curso.');
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
        $this->actingAs($otherTeacher)
            ->get(route('syllabi.index'))
            ->assertOk()
            ->assertDontSee('Sílabo de Sistemas I');

        $this->actingAs($admin)
            ->get(route('syllabi.index'))
            ->assertOk()
            ->assertSee('Pendiente de aprobación')
            ->assertSee('Subido por '.$teacher->name);
        $this->patch(route('syllabi.publication', $syllabus), ['is_published' => '1'])
            ->assertRedirect(route('syllabi.index'));
        $this->actingAs($teacher)
            ->get(route('syllabi.index'))
            ->assertOk()
            ->assertSee('Sílabo de Sistemas I');
        $this->get(route('syllabi.download', $syllabus))->assertDownload('sistemas.pdf');
        $this->actingAs($otherTeacher)
            ->get(route('syllabi.download', $syllabus))
            ->assertForbidden();
    }

    public function test_admin_manages_weekly_schedules_and_teachers_only_see_their_own(): void
    {
        $admin = User::factory()->create(['role' => User::ROLE_ADMIN]);
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $course = Course::create([
            'code' => 'PRG-301',
            'name' => 'Programación avanzada',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        $overlapCourse = Course::create([
            'code' => 'BD-301',
            'name' => 'Bases avanzadas',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
        ]);
        Course::create([
            'code' => 'DB-301',
            'name' => 'Datos I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $otherTeacher->id,
        ]);

        $this->actingAs($admin)
            ->post(route('schedules.store'), [
                'course_id' => $course->id,
                'day_of_week' => '1',
                'starts_at' => '08:00',
                'ends_at' => '09:30',
                'classroom' => 'Aula 10',
            ])
            ->assertRedirect(route('schedules.index'));

        $this->assertDatabaseHas('course_schedules', [
            'course_id' => $course->id,
            'day_of_week' => 1,
            'classroom' => 'Aula 10',
        ]);
        $this->actingAs($teacher)
            ->get(route('schedules.index'))
            ->assertOk()
            ->assertSee('Programación avanzada')
            ->assertDontSee('Bases avanzadas');

        $this->actingAs($admin)
            ->from(route('schedules.create'))
            ->post(route('schedules.store'), [
                'course_id' => $overlapCourse->id,
                'day_of_week' => '1',
                'starts_at' => '09:00',
                'ends_at' => '10:00',
                'classroom' => 'Aula 12',
            ])
            ->assertSessionHasErrors('starts_at');

        $this->actingAs($teacher)
            ->post(route('schedules.store'), [
                'course_id' => $course->id,
                'day_of_week' => '2',
                'starts_at' => '08:00',
                'ends_at' => '09:00',
            ])
            ->assertRedirect(route('attendances.index'));
    }

    public function test_teacher_records_multiple_grades_only_for_assigned_course_students(): void
    {
        $teacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $otherTeacher = User::factory()->create(['role' => User::ROLE_TEACHER]);
        $studyPlan = StudyPlan::create(['code' => 'redes', 'name' => 'Redes', 'is_active' => true]);
        $otherPlan = StudyPlan::create(['code' => 'marketing', 'name' => 'Marketing', 'is_active' => true]);
        $course = Course::create([
            'code' => 'RED-101',
            'name' => 'Redes I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $teacher->id,
            'study_plan_id' => $studyPlan->id,
        ]);
        $otherCourse = Course::create([
            'code' => 'MKT-101',
            'name' => 'Marketing I',
            'section' => 'A',
            'term' => '2026-II',
            'teacher_id' => $otherTeacher->id,
            'study_plan_id' => $otherPlan->id,
        ]);
        $student = Student::create([
            'student_code' => 'RED-001',
            'document_number' => '12345001',
            'first_name' => 'Ana',
            'last_name' => 'Torres',
            'program' => $studyPlan->name,
            'study_plan_id' => $studyPlan->id,
            'is_active' => true,
        ]);
        $otherStudent = Student::create([
            'student_code' => 'MKT-001',
            'document_number' => '12345002',
            'first_name' => 'Luis',
            'last_name' => 'Ramos',
            'program' => $otherPlan->name,
            'study_plan_id' => $otherPlan->id,
            'is_active' => true,
        ]);
        $this->actingAs($teacher)
            ->post(route('grades.assessments.store', $course), [
                'title' => 'Práctica 1',
                'assessment_date' => today()->toDateString(),
            ])
            ->assertRedirect(route('grades.show', $course));

        $assessment = Assessment::where('course_id', $course->id)->firstOrFail();
        $this->put(route('grades.save', $course), [
            'scores' => [$assessment->id => [$student->id => '18.50']],
        ])->assertRedirect(route('grades.show', $course));
        $this->assertDatabaseHas('grades', [
            'assessment_id' => $assessment->id,
            'student_id' => $student->id,
            'score' => 18.50,
        ]);
        $this->get(route('grades.show', $course))
            ->assertOk()
            ->assertSee('Práctica 1')
            ->assertSee('18.50');

        $this->post(route('grades.assessments.store', $course), [
            'title' => 'Práctica 2',
            'assessment_date' => today()->toDateString(),
        ])->assertRedirect(route('grades.show', $course));
        $secondAssessment = Assessment::where('title', 'Práctica 2')->firstOrFail();
        $this->put(route('grades.save', $course), [
            'scores' => [$secondAssessment->id => [$student->id => '15']],
        ])->assertRedirect(route('grades.show', $course));
        $this->get(route('grades.show', $course))->assertSee('16.75');

        $this->from(route('grades.show', $course))
            ->put(route('grades.save', $course), [
                'scores' => [$assessment->id => [$otherStudent->id => '15']],
            ])
            ->assertSessionHasErrors('scores');
        $this->actingAs($otherTeacher)
            ->get(route('grades.show', $course))
            ->assertForbidden();
        $this->actingAs($teacher)
            ->post(route('grades.assessments.store', $course), [
                'title' => 'Fuera de escala',
                'assessment_date' => today()->toDateString(),
            ])
            ->assertRedirect(route('grades.show', $course));
        $invalidAssessment = Assessment::where('title', 'Fuera de escala')->firstOrFail();
        $this->from(route('grades.show', $course))
            ->put(route('grades.save', $course), [
                'scores' => [$invalidAssessment->id => [$student->id => '21']],
            ])
            ->assertSessionHasErrors('scores.'.$invalidAssessment->id.'.'.$student->id);
    }

    public function test_legacy_mesa_de_partes_tables_and_accounts_are_removed(): void
    {
        $user = User::factory()->create(['role' => User::ROLE_ADMIN]);
        Schema::table('users', function (Blueprint $table): void {
            $table->boolean('is_active')->default(true);
            $table->string('oficina')->nullable();
            $table->string('office_number')->nullable();
        });
        Schema::create('estudiantes', fn (Blueprint $table) => $table->id());
        Schema::create('expedientes', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('user_id')->constrained();
        });
        Schema::create('expediente_adjuntos', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('expediente_id')->constrained();
        });
        Schema::create('expediente_derivaciones', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('expediente_id')->constrained();
        });
        DB::table('expedientes')->insert(['user_id' => $user->id]);
        DB::table('migrations')->insert([
            'migration' => '2026_10_20_000002_create_expedientes_table',
            'batch' => 1,
        ]);
        DB::table('sessions')->insert([
            'id' => 'mesa-session',
            'user_id' => $user->id,
            'payload' => 'legacy',
            'last_activity' => time(),
        ]);
        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => 'legacy',
        ]);

        $migration = require database_path('migrations/2026_11_16_000000_remove_legacy_mesa_de_partes_data.php');
        $migration->up();

        foreach (['estudiantes', 'expedientes', 'expediente_adjuntos', 'expediente_derivaciones'] as $table) {
            $this->assertFalse(Schema::hasTable($table));
        }
        $this->assertSame(0, User::count());
        $this->assertDatabaseCount('sessions', 0);
        $this->assertDatabaseCount('password_reset_tokens', 0);
        $this->assertDatabaseMissing('migrations', ['migration' => '2026_10_20_000002_create_expedientes_table']);
        $this->assertFalse(Schema::hasColumn('users', 'is_active'));
        $this->assertFalse(Schema::hasColumn('users', 'oficina'));
        $this->assertFalse(Schema::hasColumn('users', 'office_number'));
    }
}
