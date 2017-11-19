<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\JobCard;

class ExternalService extends Model
{
    const TYPE_VEHICLE = 'Vehicle';
    const TYPE_PARTS = 'Parts';

    protected $fillable = [
        'job_card_id', 'user_id', 'status', 'type', 'mechanic_findings', 'raw', 'vendor_id', 'approximate_cost',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function lines()
    {
        return $this->hasMany(ExternalServiceParts::class);
    }
}
