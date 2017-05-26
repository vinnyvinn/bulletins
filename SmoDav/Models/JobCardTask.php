<?php

namespace SmoDav\Models;

class JobCardTask extends SmoDavModel
{
    protected $fillable = [
        'workshop_job_task_id', 'employee_id', 'employee_name', 'start_time', 'total_minutes', 'status'
    ];
}
