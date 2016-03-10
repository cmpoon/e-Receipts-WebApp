<?php

namespace App\Http\Controllers;

use App\Category;
use DB;
use Illuminate\Http\Request;

use App\Http\Requests;

class BudgetController extends Controller
{
    //


    public function getIndex(){

        $grandTotal = DB::table('receipts')
            ->select(DB::raw('SUM(total) as grandtotal'))
            ->where('status','active')
            ->first()->grandtotal;

        ////values add up to 100(category value/totalvalue * 100)
        $category_grouped = DB::table('items')
            ->select('category_id', DB::raw('SUM(subtotal) as spend'))
            ->groupBy('category_id')
            ->get();

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


        return view("budget.index", ['categories' => $categories, 'grandtotal' => $grandTotal]);
    }


    public function getCategory($id){

        $category = Category::where('id', $id)->firstOrFail();


        $grandTotal = DB::table('receipts')
            ->select(DB::raw('SUM(total) as grandtotal'))
            ->where('status','active')
            ->first()->grandtotal;

        ////values add up to 100(category value/totalvalue * 100)
        $category_data = DB::table('items')
            ->select('category_id', DB::raw('SUM(subtotal) as spend'))
            ->groupBy('category_id')
            ->where('category_id',$id)
            ->first();

        $percentage = round($category_data->spend/$grandTotal*100);


        $items= $category->items()->with("receipt")->get();
        //need to group by vendor_id

        $vendor_spend = array();

        foreach($items as $item){
            $vendor = $item->receipt->vendor()->first();

            if (!isset($vendor_spend[$vendor->name])){
                $vendor_spend[$vendor->name] = 0;
            }

            $vendor_spend[$vendor->name] += $item->subtotal;
        }

        return view("budget.category",['category'=>$category,'vendors'=>$vendor_spend,'percentage'=>$percentage,'spend'=>$category_data->spend, 'grandtotal'=>$grandTotal]);
    }
}
