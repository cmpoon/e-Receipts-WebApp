<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function receipts()
    {
        return $this->hasMany('App\Receipt');
    }


    public function items()
    {
        return $this->hasMany('App\Item');
    }


    public function budgets()
    {
        return $this->hasMany('App\Budget');
    }


    public function vouchers()
    {
        return $this->hasMany('App\Voucher');
    }

    public function categories()
    {
        return $this->hasManyThrough('App\Category', 'App\Item');
    }


}
