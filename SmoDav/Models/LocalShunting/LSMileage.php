<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use App\User;
use SmoDav\Models\Vehicle;

class LSMileage extends Model
{
    protected $fillable = [
        'contract_id',
        'vehicle_id',
        'amount',
        'is_advance',
        'narration',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
}
