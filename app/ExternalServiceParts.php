<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExternalServiceParts extends Model
{
    protected $fillable = [
        'external_service_id', 'item_id', 'item_name', 'item_description', 'item_code', 'quantity', 'location',
        'status',
    ];

    public function externalService()
    {
        return $this->belongsTo(ExternalService::class);
    }
}
