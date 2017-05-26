<?php

namespace SmoDav\Models;

class WorkshopJobTask extends SmoDavModel
{
    protected $fillable = ['workshop_job_operation_id', 'name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function operation()
    {
        return $this->belongsTo(WorkshopJobOperation::class, 'workshop_job_operation_id');
    }
}
