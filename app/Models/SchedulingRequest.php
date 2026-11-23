<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchedulingRequest extends Model
{
    protected $fillable = [
        'lecturer_name',
        'course_unit',
        'class_size',
        'date',
        'start_time',
        'end_time',
        'requires_projector',
        'required_resource',
        'is_recurring',
        'repeat_until',
        'status',
        'rejection_reason',
    ];

    public function schedule()
    {
        return $this->hasOne(Schedule::class, 'request_id');
    }
}
