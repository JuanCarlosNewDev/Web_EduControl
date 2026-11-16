<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseSchedule extends Model
{
    use HasFactory;

    public const DAYS = [
        1 => 'Lunes',
        2 => 'Martes',
        3 => 'Miércoles',
        4 => 'Jueves',
        5 => 'Viernes',
        6 => 'Sábado',
        7 => 'Domingo',
    ];

    protected $fillable = [
        'course_id',
        'day_of_week',
        'starts_at',
        'ends_at',
        'classroom',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
