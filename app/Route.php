<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use SmoDav\Models\Checkpoint;

class Route extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'source', 'destination', 'distance', 'fuel_required', 'allowance_amount', 'return_mileage'
    ];

    public function checkpoints()
    {
        return $this->belongsToMany(Checkpoint::class, 'checkpoint_routes')
            ->withPivot([
                'number', 'minutes'
            ]);
    }
}
