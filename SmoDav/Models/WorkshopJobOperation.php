<?php

namespace SmoDav\Models;

class WorkshopJobOperation extends SmoDavModel
{
    protected $fillable = ['workshop_job_type_id', 'name'];

    public function type()
    {
        return $this->belongsTo(WorkshopJobType::class, 'workshop_job_type_id');
    }
}
