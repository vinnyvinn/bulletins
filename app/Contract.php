<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use SmoDav\Models\CargoClassification;
use SmoDav\Models\CargoType;
use SmoDav\Models\CarriagePoint;
use SmoDav\Models\Delivery;
use SmoDav\Support\Constants;
use SmoDav\Models\Journey;
use SmoDav\Models\Vehicle;
use App\ContractConfig;
use SmoDav\Models\LocalShunting\LSFuel;
use SmoDav\Models\LocalShunting\LSGatePass;
use SmoDav\Models\LocalShunting\LSDelivery;
use SmoDav\Models\LocalShunting\LSMileage;

class Contract extends Model
{
    const PER_KM = 'Per KM';
    const PER_HR = 'Per Hr';
    const PER_TONNE = 'Per Tonne';

    protected $fillable = [
        'raw', 'cargo_classification_id', 'cargo_type_id', 'trucks_allocated', 'job_description',
        'capture_loading_weights', 'capture_offloading_weights', 'ls_loading_weights', 'ls_offloading_weights',
        'lh_loading_weights', 'lh_offloading_weights', 'loading_point_id', 'unloading_points', 'enquiry_from',
        'contract_head', 'packages_captured', 'estimated_days', 'lot_number', 'shipping_line', 'berth_no',
        'vessel_name', 'berthing_date', 'vessel_arrival_date', 'shifts', 'no_of_shifts',
        'no_of_shifts', 'stock_item_id', 'client_id', 'route_id', 'name', 'rate', 'amount', 'start_date',
        'end_date', 'quantity', 'status', 'subcontracted', 'sub_company_name', 'sub_address_1', 'sub_address_2',
        'sub_address_3', 'sub_address_4', 'sub_delivery_to', 'sub_delivery_address','sub_delivery_address_2',
        'sub_delivery_address_3','sub_delivery_address_4','user_id', 'ignore_delivery_note', 'allow_route_change'
    ];


    public function classification()
    {
        return $this->belongsTo(CargoClassification::class, 'cargo_classification_id');
    }

    public function cargoType()
    {
        return $this->belongsTo(CargoType::class, 'cargo_type_id');
    }

    public function loadingPoint()
    {
        return $this->belongsTo(CarriagePoint::class, 'loading_point_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'DCLink')->select(array('DCLink', 'Name'));
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function scopeCurrent($builder)
    {
        return $builder->where('end_date', '>=', Carbon::now());
    }

    public function scopePending($builder)
    {
        return $builder->where('status', Constants::STATUS_PENDING);
    }

    public function scopeOpen($builder)
    {
        return $builder->where('status', Constants::STATUS_APPROVED);
    }

    public function scopeClosed($builder)
    {
        return $builder->where('status', Constants::STATUS_CLOSED);
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class);
    }

    public function deliveries()
    {
        return $this->hasManyThrough(Delivery::class, Journey::class);
    }

    public function user()
    {
      return $this->belongsTo(User::class);
    }

    public function vehicles()
    {
      return $this->hasMany(Vehicle::class);
    }

    public function contractConfig()
    {
      return $this->hasOne(contractConfig::class);
    }

    public function lsfuels()
    {
      return $this->hasMany(LSFuel::class);
    }

    public function lsgatepasses()
    {
      return $this->hasManyThrough(LSGatePass::class, Vehicle::class, 'contract_id', 'vehicle_id', 'id');
    }

    public function lsdeliveries()
    {
      return $this->hasMany(LSDelivery::class);
    }

    public function lsmileages()
    {
      return $this->hasMany(LSMileage::class);
    }
}
