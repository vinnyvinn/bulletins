<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Station;
use SmoDav\Models\Vehicle;
use App\RouteCard;
use App\User;
use App\Driver;

class LSDelivery extends Model
{
    protected $fillable = [
        'narration',
        'loading_gross_weight',
        'loading_tare_weight',
        'loading_net_weight',
        'loading_weighbridge_number',
        'offloading_gross_weight',
        'offloading_tare_weight',
        'offloading_net_weight',
        'offloading_weighbridge_number',
        'loading_time',
        'offloading_time',
        'status',
        'user_id',
        'station_id',
        'vehicle_id',
        'contract_id',
        'temporary_driver'
    ];

    public function routecard()
    {
        return $this->hasOne(RouteCard::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function temporary_driver()
    {
        return $this->belongsTo(Driver::class, 'temporary_driver', 'id');
    }

    public function temporaryDriver()
    {
        return $this->belongsTo(Driver::class, 'temporary_driver', 'id');
    }

    public function station()
    {
        return $this->belongsTo(Station::class);
    }
}
