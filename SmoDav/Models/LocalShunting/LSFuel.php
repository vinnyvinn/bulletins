<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\Vehicle;
use App\User;

class LSFuel extends Model
{
  protected $fillable = [
    'station_id','contract_id','vehicle_id','narration','fuel_issued',
    'current_km','total_in_tank','status','created_by','approved_by','under_trips',
    'reason'
  ];


  public function user()
  {
    return $this->belongsTo(User::class, 'created_by');
  }

  public function approved_by()
  {
    return $this->belongsTo(User::class, 'approved_by');
  }

  public function vehicle()
  {
    return $this->belongsTo(Vehicle::class);
  }
}
