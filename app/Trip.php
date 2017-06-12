<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'truck_id', 'contract_id', 'driver_id', 'route_id', 'pre_loading_checklist_id', 'delivery_note_id',
        'is_complete', 'trip_date', 'source', 'destination', 'receive_delivery_note_id', 'stage'
    ];

    public function preLoadingChecklist()
    {
        return $this->belongsTo(Checklist::class, 'pre_loading_checklist_id');
    }

    public function deliveryNote()
    {
        return $this->belongsTo(Checklist::class, 'delivery_note_id');
    }

    public function receiveNote()
    {
        return $this->belongsTo(Checklist::class, 'receive_delivery_note_id');
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class, 'truck_id');
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function contract()
    {
        return $this->belongsTo(Contract::class, 'contract_id');
    }

    public function route()
    {
        return $this->belongsTo(Route::class, 'route_id');
    }
}