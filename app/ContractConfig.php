<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Contract;

class ContractConfig extends Model
{
    protected $fillable = ['contract_id','average_fuel','supervisors','casuals','operators','mechanics'];

    public function contract()
    {
      return $this->hasOne(Contract::class);
    }
}
