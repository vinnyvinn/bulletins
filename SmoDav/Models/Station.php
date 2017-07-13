<?php

namespace SmoDav\Models;

use App\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class Station extends SmoDavModel
{
    use SoftDeletes;

    protected $fillable = ['name', 'location'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
