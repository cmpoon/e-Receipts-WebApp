<?php

namespace App\Http\Controllers;

use App\Receipt;
use Illuminate\Http\Request;

use App\Http\Requests;
use Symfony\Component\HttpFoundation\Response;

class UploadController extends Controller {


    //
    public function getIndex(Request $request) {
        //Show outstanding uuid here or false

        $userid = null;

        try {
            $receipt = Receipt::where('status', 'new')->take(1)->with('vendor')->get();

            if ($receipt->count() >= 1) {
                $rData = $receipt->first();
                return response()->json(['outstanding' => $rData->uuid, 'price'=>$rData->total,
                    'vendor' =>$rData->vendor->name,'id'=>$rData->id]);
            } else {
                return response()->json(['outstanding' => false]);
            }

        } catch (ModelNotFoundException $exp) {
            return response()->json(['outstanding' => false]);
        }

    }

    public function getLink(Request $request) {
        //Show outstanding uuid here or false

        $uuid = $request->input("uuid");

        if ($uuid && $uuid !== "test") {
                $receipt = Receipt::where('status', 'new')->where('uuid', $uuid)->firstOrFail();

        } else {
            $receipt = Receipt::where('status', 'new')->firstOrFail();
        }

        if ($receipt) {


            $receipt->status = "active";

            $receipt->save();

            return response()->json(['link' => true]);
        }

        return response()->json(['link' => false]);

    }


}
