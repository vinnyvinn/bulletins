<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $fillable = ['option_key', 'option_value'];

    public $timestamps = false;

    const OPTION_ITEM_GROUP = 'spares_item_group';
}
