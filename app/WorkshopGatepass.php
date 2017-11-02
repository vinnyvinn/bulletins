<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\JobCard;

class WorkshopGatepass extends Model
{
    const TYPE_VEHICLE = 'Vehicle';
    const TYPE_PARTS = 'Parts';
    const TYPE_EXTERNAL_VEHICLE = 'External Vehicle';
    const TYPE_EXTERNAL_PARTS = 'External Parts';

    protected $fillable = [
        'job_card_id', 'driver_id', 'type', 'supplier_name', 'fuel_reading', 'km_reading', 'remarks', 'parts',
        'status', 'supplier_id', 'external_service_id',
    ];

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function service()
    {
        return $this->belongsTo(ExternalService::class, 'external_service_id');
    }
}
