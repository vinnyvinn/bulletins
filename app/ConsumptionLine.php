<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConsumptionLine extends Model
{
    protected $fillable = [
        'requisition_line_id', 'consumed', 'user_id'
    ];
}
