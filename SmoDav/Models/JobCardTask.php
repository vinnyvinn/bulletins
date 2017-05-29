<?php

namespace SmoDav\Models;

class JobCardTask extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'operation_id', 'workshop_job_task_id', 'employee_id', 'start_time', 'total_minutes', 'status'
    ];
}
