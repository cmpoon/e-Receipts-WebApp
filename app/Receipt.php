<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid','total', 'time', 'data', 'status'
    ];


    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }

}
