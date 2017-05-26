<?php

namespace SmoDav\Models;

class WorkshopJobType extends SmoDavModel
{
    protected $fillable = ['service_type', 'name'];

    protected $hidden = ['created_at', 'updated_at'];

    public function operations()
    {
        return $this->hasMany(WorkshopJobOperation::class, 'workshop_job_type_id');
    }
}
