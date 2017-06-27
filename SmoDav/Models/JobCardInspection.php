<?php

namespace SmoDav\Models;

class JobCardInspection extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'workshop_inspection_check_list_id', 'employee_id', 'status'
    ];
}
