<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Journey;
use App\RouteCard;
use App\User;

class LSDelivery extends Model
{
    protected $fillable = [
        'journey_id',
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
        'raw',
        'status',
        'bags_loaded',
        'user_id',
        'station_id'
    ];

    public function journey()
    {
        return $this->belongsTo(Journey::class);
    }

    public function routecard()
    {
        return $this->hasOne(RouteCard::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }
}
