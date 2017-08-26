<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Contract;

class ContractConfig extends Model
{
    protected $fillable =
          [
            'contract_id',
            'average_fuel_per_trip',
            'supervisors',
            'supervisors_rate',
            'casuals',
            'casuals_rate',
            'operators',
            'operators_rate',
            'mechanics',
            'mechanics_rate',
            'drivers_rate',
            'tail_gates',
            'tail_gates_rate',
            'trips_before_refuel',
            'initial_fuel',
            'refuel_1',
            'refuel_2',
            'refuel_3'
          ];

    public function contract()
    {
      return $this->hasOne(Contract::class);
    }
}
