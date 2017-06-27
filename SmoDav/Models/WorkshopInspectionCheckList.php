<?php

namespace SmoDav\Models;

class WorkshopInspectionCheckList extends SmoDavModel
{
    protected $fillable = ['name'];

    protected $hidden = ['created_at', 'updated_at'];
}
