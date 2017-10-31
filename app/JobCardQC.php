<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\JobCard;

class JobCardQC extends Model
{
    protected $fillable = [
        'job_card_id', 'status', 'tasks', 'remarks'
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }
}
