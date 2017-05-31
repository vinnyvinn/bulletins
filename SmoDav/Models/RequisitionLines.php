<?php

namespace SmoDav\Models;

class RequisitionLines extends SmoDavModel
{
    protected $fillable = [
        'requisition_id', 'item_id', 'item_name', 'requested_quantity', 'approved_quantity', 'issued_quantity'
    ];

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
