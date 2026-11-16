<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Contracts\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $month = Attendance::query()->whereBetween('attendance_date', [
            now()->startOfMonth()->toDateString(),
            now()->endOfMonth()->toDateString(),
        ]);
        $monthTotal = (clone $month)->count();
        $monthPresent = (clone $month)->whereIn('status', ['presente', 'tardanza'])->count();

        return view('dashboard', [
            'studentCount' => Student::count(),
            'courseCount' => Course::count(),
            'todayCount' => Attendance::whereDate('attendance_date', today())->count(),
            'attendanceRate' => $monthTotal === 0 ? 0 : (int) round($monthPresent / $monthTotal * 100),
            'recentAttendances' => Attendance::with(['student', 'course'])
                ->latest('attendance_date')
                ->latest('id')
                ->limit(7)
                ->get(),
        ]);
    }
}
