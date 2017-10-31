<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\JobCard;

class JobCardProgress extends Model
{
    protected $fillable = [
        'job_card_id', 'user_id', 'update', 'job_status', 'job_depends_on', 'employee_id'
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
