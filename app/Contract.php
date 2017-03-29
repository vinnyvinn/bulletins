<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    const PER_KM = 'Per KM';
    const PER_HR = 'Per Hr';
    const PER_TONNE = 'Per Tonne';

    protected $fillable = [
        'client_id', 'route_id', 'name', 'rate', 'amount', 'start_date', 'end_date', 'quantity', 'stock_item_id'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id', 'DCLink');
    }

    public function route()
    {
        return $this->belongsTo(Route::class);
    }

    public function scopeCurrent($builder)
    {
        return $builder->where('end_date', '>=', Carbon::now());
    }
}
