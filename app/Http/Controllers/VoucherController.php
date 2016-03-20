<?php

namespace App\Http\Controllers;

use App\Voucher;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class VoucherController extends Controller
{
    //

    public function getIndex(){

        $vouchers = Voucher::where('user_id', Auth::user()->id)
            ->where('status','active')
            ->where('expiration', '<', Carbon::now())
            ->orderBy('expiration', 'asc')
            ->with("vendor")
            ->get();

        //Initialise array
        $vendors = array();

        if ($vouchers->count() > 0) {

            foreach ($vouchers as $voucher){

                if (!isset($vendors[$voucher->vendor->name])){
                    $vendors[$voucher->vendor->name] = array();
                }

                $vendors[$voucher->vendor->name][] = $voucher;

            }

        }

        return view('vouchers.index', [
            'vendors' => $vendors
        ]);

    }

    public function getVoucher($id){
        
        $voucher = Voucher::where('id', $id)
            ->where('user_id', Auth::user()->id)
            ->where('status','active')
            ->where('expiration', '<', Carbon::now())
            ->orderBy('expiration', 'asc')
            ->with("vendor")
            ->firstOrFail();

        return view('vouchers.detail', [
            'voucher' => $voucher
        ]);
    }
}
