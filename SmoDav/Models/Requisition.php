<?php

namespace SmoDav\Models;

class Requisition extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'user_id', 'status'
    ];
}
