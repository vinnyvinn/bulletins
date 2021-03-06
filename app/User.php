<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use SmoDav\Models\Mileage;
use SmoDav\Models\Journey;
use SmoDav\Models\Delivery;
use SmoDav\Models\Station;
use SmoDav\Models\LocalShunting\GatePass;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'permissions', 'username'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getNameAttribute($name)
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function get_gravatar($s = 40, $d = 'mm', $r = 'g', $img = false, $atts = [])
    {
        $email = $this->email;
        $url = 'http://www.gravatar.com/avatar/';
        $url .= \md5(\strtolower(\trim($email)));
        $url .= "?s=$s&d=$d&r=$r";

        if (!empty($this->photo)) {
            $url = asset('uploads/photo/' . $this->photo);
        }

        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }

            $url .= ' />';
        }

        return $url;
    }

    public function get_fullname()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function joinedShops()
    {
        return $this->belongsToMany('App\Shop');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function isSuperAdmin()
    {
        return $this->user_type == 'super_admin';
    }

    public function isSubAdmin()
    {
        return $this->user_type == 'sub_admin';
    }

    public function isShopAdmin()
    {
        return $this->user_type == 'shop_admin';
    }

    public function isUser()
    {
        return $this->user_type == 'user';
    }

    public function scopeUser($query)
    {
        return $query->where('user_type', 'user');
    }

    public function messages()
    {
        return $this->hasMany('App\Message');
    }

    public function messages_unread_count()
    {
        if ($this->isSuperAdmin()) {
            $unread = Message::where('user_id', 0)->where('parent_id', 0)->where('is_read', 0)->count();
        } else {
            $unread = $this->hasMany('App\Message')->where('parent_id', 0)->where('is_read', 0)->count();
        }

        return $unread;
    }

    public function fuels_approved()
    {
        return $this->hasMany(Fuel::class);
    }

    public function mileages()
    {
        return $this->hasMany(Mileage::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function journeys()
    {
        return $this->hasMany(Journey::class);
    }

    public function deliveries()
    {
        return $this->hasMany(Delivery::class);
    }

    public function stations()
    {
        return $this->belongsToMany(Station::class);
    }

    public function gatepass()
    {
        return $this->hasOne(GatePass::class);
    }

    public function has($permission)
    {
        $permissions = json_decode($this->permissions);

        if (in_array('*', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }
}
