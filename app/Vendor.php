<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'tel_number', 'vat_number'
    ];

    public function items()
    {
        return $this->hasMany('App\Item');
    }


    public function vouchers()
    {
        return $this->hasMany('App\Voucher');
    }

    public function demoItems()
    {
        return $this->hasMany('App\DemoItem');
    }


    public function category()
    {
        return $this->belongsTo('App\Category');
    }


    public function parent()
    {
        return $this->belongsTo('App\Vendor','parent_id');
    }

    public function children(){
        return $this->hasMany('App\Vendor', ' parent_id');
    }
}
