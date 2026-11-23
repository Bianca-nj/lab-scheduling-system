<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    protected $fillable = [
        'request_id',
        'lab_id',
        'resource_id',
        'schedule_date',
        'start_time',
        'end_time',
        'decision',
        'reason'
    ];

    public function laboratory()
    {
        return $this->belongsTo(\App\Models\Laboratory::class, 'lab_id');
    }

    public function resource()
    {
        return $this->belongsTo(\App\Models\Resource::class, 'resource_id');
    }

    public function request()
    {
        return $this->belongsTo(\App\Models\SchedulingRequest::class, 'request_id');
    }
}
