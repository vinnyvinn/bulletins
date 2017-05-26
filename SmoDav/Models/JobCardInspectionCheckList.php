<?php

namespace SmoDav\Models;

class JobCardInspectionCheckList extends SmoDavModel
{
    protected $fillable = [
        'job_card_inspection_id', 'workshop_inspection_check_list_id', 'employee_id', 'status'
    ];
}
