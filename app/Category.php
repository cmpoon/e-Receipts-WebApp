<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'categories';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];


    public function items()
    {
        return $this->hasMany('App\Item');
    }

    public function budgets()
    {
        return $this->hasMany('App\Budget');
    }


    public function receipts()
    {
        return $this->hasManyThrough('App\Receipt', 'App\Item');
    }


    public function vendors()
    {
        return $this->hasMany('App\Vendor');
    }


    public function demoItems()
    {
        return $this->hasMany('App\DemoItem');
    }


    public function parent()
    {
        return $this->belongsTo('App\Category','parent_id');
    }

    public function children(){
        return $this->hasMany('App\Category', ' parent_id');
    }
}
