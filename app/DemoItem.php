<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DemoItem extends Model
{
    //
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'demoitems';


    protected $fillable = [
        'name', 'price', 'unit'
    ];

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function vendor()
    {
        return $this->belongsTo('App\Vendor');
    }


}
