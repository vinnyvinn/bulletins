<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Vehicle;
use App\User;

class LSGatePass extends Model
{
    protected $fillable = ['vehicle_id','user_id'];

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
