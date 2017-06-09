<?php

namespace SmoDav\Models;

use App\Contract;
use App\Driver;
use App\Truck;

class Journey extends SmoDavModel
{
    protected $fillable = [
        'status', 'is_contract_related', 'contract_id', 'journey_type', 'job_date', 'truck_id', 'driver_id', 'ref_no',
        'route_id', 'job_description', 'enquiry_from', 'subcontracted', 'sub_company_name', 'sub_address_1',
        'sub_address_2', 'sub_address_3', 'sub_address_4', 'raw'
    ];

    public function contract()
    {
        return $this->belongsTo(Contract::class);
    }

    public function truck()
    {
        return $this->belongsTo(Truck::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
