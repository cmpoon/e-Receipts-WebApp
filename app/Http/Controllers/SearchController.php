<?php

namespace App\Http\Controllers;

use App\Receipt;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    //


    static function receiptsToDays($receipts){
        $days = array();

        if ($receipts->count() > 0) {

            //Initialise array
            $lastday = "";
            $day = -1;
            $days = array();

            foreach ($receipts as $receipt){

                $today = date('Y-m-d', strtotime($receipt->time));

                //Create new day
                if ($today != $lastday){
                    $lastday = $today;
                    $day++;
                    $days[$day] = array();
                    $days[$day]['date'] = $today;
                    $days[$day]['count'] = 0;
                    $days[$day]['receipts'] = array();
                }

                $days[$day]['receipts'][] = $receipt;
                $days[$day]['count']++;

            }

        }



        return $days;
    }

    /**
     * Display a list of all of the user's receipt.
     *
     * @param  Request  $request
     * @return Response
     */
    public function getIndex()
    {
        $receipts = Receipt::where('user_id', Auth::user()->id)->where('status','active')->orderBy('time', 'desc')->get();

        $days = $this->receiptsToDays($receipts);

        return view('search.index', [
            'days' => $days
        ]);
    }

    public function getDate(Request $request){

        $date = $request->input('date');

        if (empty($date)){
            $receipts = Receipt::where('user_id', Auth::user()->id)->where('status','active')->orderBy('time', 'desc')->get();

        }else {

            $start = new \DateTime($date);

            $start->setTime(0, 0, 0);

            $end = new \DateTime($date);
            $end->setTime(0, 0, 0);
            $end->add(new \DateInterval("P1D"));

            $receipts = Receipt::where('user_id', Auth::user()->id)->where('status', 'active')->where('time', '>', $start)
                ->where('time', '<', $end)->orderBy('time', 'desc')->get();
        }

        $days = $this->receiptsToDays($receipts);

        return view('search.day', [
            'days' => $days
        ]);


    }

    public function getReceipt($id){
        $receipt = Receipt::where('user_id', Auth::user()->id)->where('id',$id)->firstOrfail();

        return view('receipt.index', [
            'receipt' => $receipt
        ]);

    }


    public function getDelete($id){
        $receipt = Receipt::where('user_id', Auth::user()->id)->where('id',$id)->firstOrfail();
        $receipt -> status = "void";
        $receipt -> save();
        return redirect()->action('SearchController@getIndex');

    }
}
