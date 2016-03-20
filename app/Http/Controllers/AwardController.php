<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Category;
use App\Receipt;
use Auth;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class AwardController extends Controller {
    //

    public function getIndex() {

        //saver
        //TODO: Vouchers Not implemented

        //cheapshopper
        $cheapshopper = false;
        $foodSpend = $this->getCategorySpend("food");
        $foodBudget = $this->getCategoryBudget("food");
        if (!is_null($foodBudget) && $foodSpend <= $foodBudget) {
            $cheapshopper = true;
        }

        //crazycollector
        $receiptsInWeek = Receipt::where('user_id', Auth::user()->id)
            ->where('status', 'active')
            ->where("time", '>', Carbon::now()->subMonth())
            ->count();
        echo $receiptsInWeek;
        $crazycollector = $receiptsInWeek >= 50;

        //projectionperfection
        $totalBudget = $this->getCategoryBudget("All Categories");
        $regression = $this->getRegression();

        var_dump($totalBudget, $regression);
        $projectionperfection = $regression <= $totalBudget;

        return view('awards.index', [
            'saver' => false, 'cheapshopper' => $cheapshopper, 'crazycollector' => $crazycollector, 'projectionperfection' => $projectionperfection
        ]);
    }

    //Sorry for copy and pasting this code from Budget/Projection Controllers
    private function getCategorySpend($name) {
        //Food
        $category = Category::where('name', $name)->firstOrFail();

        $budget = Budget::where('user_id', Auth::user()->id)->where('category_id', $category->id)
            ->where('start', '<', Carbon::now())
            ->where('end', '>', Carbon::now())
            ->first();

        if (!is_null($budget)) {
            $start = Carbon::parse($budget->start);
            $end = Carbon::parse($budget->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        ////values add up to 100(category value/totalvalue * 100)
        $category_data = DB::table('items')
            ->select('category_id', DB::raw('SUM(subtotal) as spend'))
            ->groupBy('category_id')
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->where('user_id', Auth::user()->id)
            ->where('category_id', $category->id)
            ->first();

        return $category_data->spend;
    }

    private function getCategoryBudget($name) {

        //Food
        $category = Category::where('name', $name)->firstOrFail();

        $budget = Budget::where('user_id', Auth::user()->id)->where('category_id', $category->id)
            ->where('start', '<', Carbon::now())
            ->where('end', '>', Carbon::now())
            ->first();

        //Drill down on budget from category-specific, to total, to using grandtotal used
        if (!is_null($budget)) {
            return $budget = $budget->amount;
        } else {
            return null;
        }

    }

    private function getRegression() {

        $budget = Budget::where('user_id', Auth::user()->id)->where('category_id', 0)
            ->where('start', '<', Carbon::now())
            ->where('end', '>', Carbon::now())
            ->first();

        if (!is_null($budget)) {
            $start = Carbon::parse($budget->start);
            $end = Carbon::parse($budget->end);
        } else {
            $start = Carbon::now()->startOfMonth();
            $end = Carbon::now()->endOfMonth();
        }

        //Process receipt data into daily spend

        $receipts = Receipt::where('user_id', Auth::user()->id)->where('status', 'active')
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->orderBy('time', 'asc')->get();

        //Initialise array
        $lastday = "";

        $dailySpend = array();

        $interval = new DateInterval('P1D');
        $daterange = new DatePeriod($start, $interval, $end);


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

            return $regGrad * $numberOfDays;
        } else {
            return 0;

        }
    }
}
