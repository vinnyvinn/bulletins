<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BreakdownArea extends Model
{
    protected $fillable = ['name'];

    public function breakdowns()
    {
        return $this->hasMany(Breakdown::class);
    }
}
