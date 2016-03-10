<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Category;
use App\Item;
use App\Receipt;
use App\Vendor;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Faker\Provider\cs_CZ\DateTime;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class ProjectionController extends Controller
{
    //
    public function getIndex(Request $request) {

        $cateReq = $request->input("category");
        $cateReq = (!empty($cateReq) ? $cateReq : 0);
        $category = ($cateReq == 0 ? null :
            Category::where('id', $cateReq)->firstOrFail());

        if (!is_null($category)) {
            $budget = Budget::where('user_id', Auth::user()->id)->where('category_id', $category->id)
                ->where('start', '<', Carbon::now())
                ->where('end', '>', Carbon::now())
                ->first();
        } else {
            $budget = Budget::where('user_id', Auth::user()->id)->where('category_id', 0)
                ->where('start', '<', Carbon::now())
                ->where('end', '>', Carbon::now())
                ->first();
        }

        $budgetData = array();

        if (!is_null($budget)) {

            $start = Carbon::parse($budget->start);
            $end = Carbon::parse($budget->end);

            $budget = round($budget->amount, 2);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
            $budget = 0;
        }

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval, $end);

        $budgetData[$start->format("Y,n-1,d")] = 0;
        $budgetData[$end->format("Y,n-1,d")] = $budget;

        $dailySpend = array();

        if ($cateReq) {
            $items = Item::where('user_id', Auth::user()->id)
                ->where('category_id', $cateReq)
                ->where('time', '>', $start)
                ->where('time', '<', $end)
                ->orderBy('time', 'asc')->get();


            //Initialise array
            $lastday = "";

            //Group receipt by day
            if ($items->count() > 0) {

                foreach ($items as $item) {

                    $today = date('Y,n-1,d', strtotime($item->time));

                    //Create new day
                    if ($today != $lastday) {
                        $lastday = $today;
                        $dailySpend[$today] = 0;
                    }

                    $dailySpend[$today] += $item->subtotal;

                }

            }
        } else {
            //Process receipt data into daily spend

            $receipts = Receipt::where('user_id', Auth::user()->id)->where('status', 'active')
                ->where('time', '>', $start)
                ->where('time', '<', $end)
                ->orderBy('time', 'asc')->get();


            //Initialise array
            $lastday = "";

            //Group receipt by day
            if ($receipts->count() > 0) {

                foreach ($receipts as $receipt) {

                    $today = date('Y,n-1,d', strtotime($receipt->time));

                    //Create new day
                    if ($today != $lastday) {
                        $lastday = $today;
                        $dailySpend[$today] = 0;
                    }

                    $dailySpend[$today] += $receipt->total;

                }

            }
        }

        if (!empty($dailySpend)) {


            //Process each day

            $cumTotal = 0;
            $days = array();
            $numberOfDays = 0;
            //For each day in month
            foreach ($daterange as $date) {
                $numberOfDays++;
                $today = $date->format("Y,n-1,d");

                $now = Carbon::now();
                if ($now->lt($date)) {
                    continue;
                }

                if (isset($dailySpend[$today])) {
                    $cumTotal += $dailySpend[$today];
                }
                $days[$today] = $cumTotal;
            }

            $i = 0;
            $sumOfProduct = 0;
            $sumOfDaySq = 0;
            foreach ($days as $date => $cumulative) {
                $i++;
                $sumOfProduct += ($i * $cumulative);
                $sumOfDaySq += ($i * $i);
            }

            if ($sumOfDaySq) {
                $regGrad = $sumOfProduct / $sumOfDaySq;
            } else {
                $regGrad = 0;
            }

            $regData = array();
            $regData[$start->format("Y,n-1,d")] = 0;
            $regData[$end->format("Y,n-1,d")] = $regGrad * $numberOfDays;
        }else{
            $days = array();
            $regData = array();
            $regData[$start->format("Y,n-1,d")] = 0;
            $regData[$end->format("Y,n-1,d")] = 0;

        }

        $subtitle = ($cateReq?ucfirst($category->name)." in ":"").date('F', $start->timestamp);

        $categories = Category::all();

        $selcate = array();
        $i = 0;
        foreach($categories as $cateEntry){
            $selcate[$i] = array();
            $selcate[$i]['id'] = $cateEntry->id;
            $selcate[$i]['name'] = ucfirst($cateEntry->name);
            $selcate[$i]['selected'] = ($cateEntry->id == $cateReq? true : false);
            $i++;
        }

        return view('projection.index', [
            'days' => $days, 'budget' => $budgetData, 'projection' => $regData,
            'month' => $subtitle, 'start'=>$start->format("Y,n-1,d"),
            'end'=>$end->format("Y,n-1,d"), 'now'=>date("Y,n-1,d"),
            'categories'=>$selcate
        ]);

    }


}
