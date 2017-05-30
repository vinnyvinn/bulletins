<?php

namespace SmoDav\Models;

use App\User;
use Auth;
use SmoDav\Support\Constants;

class Requisition extends SmoDavModel
{
    protected $fillable = [
        'job_card_id', 'user_id', 'status', 'mechanic_findings', 'raw_data'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lines()
    {
        return $this->hasMany(RequisitionLines::class);
    }

    public function jobCard()
    {
        return $this->belongsTo(JobCard::class);
    }

    public function scopeOwn($builder)
    {
        return $builder->where('user_id', Auth::id());
    }

    public function scopePending($builder)
    {
        return $builder->where('status', Constants::STATUS_APPROVED);
    }
}
