<?php

namespace SmoDav\Models\LocalShunting;

use Illuminate\Database\Eloquent\Model;
use App\User;


class LSEmployeeMileage extends Model
{
  protected $fillable = [
    'contract_id',
    'employee_id',
    'amount',
    'is_advance',
    'narration',
    'user_id'
  ];

  public function user()
  {
    return $this->belongsTo(User::class);
  }
}
