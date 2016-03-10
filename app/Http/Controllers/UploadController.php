<?php

namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller
{


    //
    public function getIndex(Request $request){
        //Show outstanding uuid here or false

        try {
            $receipt = Receipt::where('status','new')->take(1)->get();

            if ($receipt->count() >= 1) {

                return response()->json(['outstanding' => $receipt->first()->uuid]);
            }else{
                return response()->json(['outstanding' => false]);
            }

        }catch(ModelNotFoundException $exp) {
            return response()->json(['outstanding' => false]);
        }

    }

    public function getLink(){
        //Show outstanding uuid here or false

        $receipt = Receipt::where('status','new')->firstOrFail();
        $receipt->status = "active";

        $receipt->save();

        return response()->json(['link' => true]);

    }




}
