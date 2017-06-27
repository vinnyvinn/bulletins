<?php

namespace SmoDav\Models;

class WorkshopJobOperation extends SmoDavModel
{
    protected $fillable = ['workshop_job_type_id', 'name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function type()
    {
        return $this->belongsTo(WorkshopJobType::class, 'workshop_job_type_id');
    }

    public function tasks()
    {
        return $this->hasMany(WorkshopJobTask::class, 'workshop_job_operation_id');
    }
}
