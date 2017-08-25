<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContractConfig extends Model
{
    protected $fillable = ['contract_id', 'rate', 'average_fuel','supervisors','casuals','operators','mechanics'];

    public function contract()
    {
        return $this->hasOne(Contract::class);
    }
}
