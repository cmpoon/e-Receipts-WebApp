<?php

namespace App\Http\Controllers;

use App\Budget;
use App\Category;
use Carbon\Carbon;
use DateTime;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\DomCrawler\Form;

class BudgetController extends Controller
{
    //


    public function getIndex(){


        $budget = Budget::where('user_id', Auth::user()->id)->where('category_id',0)
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


        $grandTotal = DB::table('receipts')
            ->select(DB::raw('SUM(total) as grandtotal'))
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->where('status','active')
            ->where('user_id', Auth::user()->id)
            ->first()->grandtotal;

        ////values add up to 100(category value/totalvalue * 100)
        $category_grouped = DB::table('items')
            ->select('category_id', DB::raw('SUM(subtotal) as spend'))
            ->groupBy('category_id')
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->where('user_id', Auth::user()->id)
            ->get();


        if (!is_null($budget)) {
            $budget = $budget->amount;
            $percentage = round($grandTotal/$budget*100);
        }else{
            $budget = "";
            $percentage = -1;
        }

        $categories = array();
        $i = 0;

        foreach ($category_grouped as $category){
            $category_name = Category::where('id', $category->category_id)->first()->name;

            $categories[$i] = array();
            $categories[$i]['last'] = false;
            $categories[$i]['name'] = ucfirst($category_name);
            //values add up to 100, (category value/totalvalue * 100)
            $categories[$i]['value'] = $category->spend;
            $categories[$i]['id'] = $category->category_id;

            $i++;
        }

        $categories[$i - 1]['last'] = true;

        $subtitle = date('F', $start->timestamp);

        return view("budget.index", ['categories' => $categories, 'grandtotal' => $grandTotal, 'budget'=>$budget, 'percentage'=>$percentage, 'subtitle' => $subtitle]);
    }


    public function getCategory($id){

        $category = Category::where('id', $id)->firstOrFail();

        $budget = Budget::where('user_id', Auth::user()->id)->where('category_id',$id)
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


        $grandTotal = DB::table('receipts')
            ->select(DB::raw('SUM(total) as grandtotal'))
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->where('status','active')
            ->where('user_id', Auth::user()->id)
            ->first()->grandtotal;


        ////values add up to 100(category value/totalvalue * 100)
        $category_data = DB::table('items')
            ->select('category_id', DB::raw('SUM(subtotal) as spend'))
            ->groupBy('category_id')
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->where('user_id', Auth::user()->id)
            ->where('category_id',$id)
            ->first();

        //Drill down on budget from category-specific, to total, to using grandtotal used
        if (!is_null($budget)) {
            $budget = $budget->amount;
            $percentage = round($category_data->spend/$budget*100);
        }else{
            $totalBudget = Budget::where('user_id', Auth::user()->id)->where('category_id',0)->first();

            if (!is_null($totalBudget)) {
                $budget = $totalBudget->amount;
                $percentage = round($category_data->spend/$budget*100);
            }else{
                $budget = "";
                $percentage = round($category_data->spend/$grandTotal*100);
            }

        }

        $items= $category->items()
            ->where('time', '>', $start)
            ->where('time', '<', $end)
            ->with("receipt")
            ->get();
        //need to group by vendor_id

        $vendor_spend = array();

        foreach($items as $item){
            $time = new DateTime($item->receipt->date);

            if ($item->receipt->status != "active"
            || $time < $start || $time > $end){
                continue;
            }

            $vendor = $item->receipt->vendor()->first();

            if (!isset($vendor_spend[$vendor->name])){
                $vendor_spend[$vendor->name] = 0;
            }


            $vendor_spend[$vendor->name] += $item->subtotal;
        }

        $subtitle = " in ".date('F', $start->timestamp);


        return view("budget.category",['category'=>$category,'vendors'=>$vendor_spend,'percentage'=>$percentage,
            'spend'=>$category_data->spend, 'grandtotal'=>$grandTotal, 'budget'=>$budget, 'subtitle' => $subtitle]);
    }

    public function postBudget(Request $request){


        $id = $request->input('id');
        $amount = trim($request->input('amount'));

        $category = null;
        if (!empty($id)) {
            $category = Category::where('id', $id)->firstOrFail();
            $budget = Budget::firstOrNew(['category_id'=>$category->id, 'user_id'=>Auth::user()->id]);

            if (empty($amount)){

                $budget->delete();

                return redirect()->action("BudgetController@getCategory", ['id'=>$id])
                    ->with('status', 'Budget deleted.');

            }else if (!is_numeric($amount)){
                return redirect()->action("BudgetController@getCategory", ['id'=>$id])
                    ->with('status', 'Invalid Input: '.$amount);
            }

            $budget->category()->associate($category);
        }else {
            $budget = Budget::firstOrNew(['user_id' => Auth::user()->id, 'category_id' => 0]);

            if (empty($amount)){

                $budget->delete();

                return redirect()->action("BudgetController@getIndex")
                    ->with('status', 'Budget deleted.');

            }else if (!is_numeric($amount)) {
                return redirect()->action("BudgetController@getIndex")
                    ->with('status', 'Invalid Input: ' . $amount );
            }

            $budget->category_id = 0;

        }

        $budget->user()->associate(Auth::user());

        $budget->amount = round($amount, 2);
        $budget->start = Carbon::now()->startOfMonth();
        $budget->end = Carbon::now()->endOfMonth();

        $budget->save();

        if (is_null($category)){

            return redirect()->action("BudgetController@getIndex")
                ->with('status', '&pound;'.$amount.' '.'Total budget set for the month.');

        }else{

            return redirect()->action("BudgetController@getCategory", ['id'=>$id])
                ->with('status', '&pound;'.$amount.' '.ucfirst($category->name).' budget set for the month.');

        }


    }
}
