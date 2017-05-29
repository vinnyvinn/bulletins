<?php

namespace SmoDav\Models;

class JobCardInspection extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'inspection', 'done_by', 'status', 'mechanic_findings'
    ];
}
