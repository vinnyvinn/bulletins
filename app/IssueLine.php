<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssueLine extends Model
{
    protected $fillable = [
        'requisition_line_id', 'issued', 'user_id'
    ];
}
