<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

class AwardController extends Controller
{
    //

    public function getIndex(){
        

        return view('awards.index', [
            'saver' => false, 'cheapshopper' => false, 'crazycollector' => true, 'projectionperfection' => false
        ]);
    }
}
