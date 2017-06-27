<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Checklist extends Model
{
    const DELIVERY_NOTE = 'Delivery Note';
    const OFFLOADING_NOTE = 'Offloading Note';

    protected $fillable = [
        'status', 'driver_id', 'truck_id', 'type', 'from_station', 'to_station', 'fields', 'inspector_id',
        'supervisor_id', 'inspectors_comments', 'supervisors_comments', 'suitable_for_loading', 'for_date'
    ];
}
