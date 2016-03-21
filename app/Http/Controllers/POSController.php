<?php

namespace App\Http\Controllers;

use App\DemoItem;
use App\Item;
use App\Receipt;
use App\Vendor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Auth;

class POSController extends Controller {
    //
    public function index(Request $request) {

        $receipt = Receipt::where('status', 'new')->first();

        if ($receipt == null) {

            //Generate receipt here
            $receipt = Receipt::create();
            $tries = 0;
            do {
                $tries++;
                //get a random vendor
                $receipt->vendor()->associate(Vendor::orderByRaw("RAND()")->first());

                $receipt->uuid = uniqid();
                $receipt->user()->associate(Auth::user());
                //Now
                $receipt->time = new \DateTime("-" . rand(0, 14) . " day");
                $receipt->total = 0;
                $receipt->data = "";
                $receipt->status = "new";

                $receipt->save();

                $numberOfItems = rand(2, 8);
                $total = 0;

                $data = array();
                $data["receiptUUID"] = $receipt->uuid;

                $data["vendor"] = array("name" => $receipt->vendor->name, "address" => $receipt->vendor->address,
                    "telNumber" => $receipt->vendor->tel_number, "vatNumber" => $receipt->vendor->vat_number);
                $data['transaction'] = array("time" => $receipt->time, "channel" => "checkout", "operator" => "Zoe Williamson");
                $data["items"] = array();

                $usedItemNames = array();

                for ($i = 0; $i < $numberOfItems; $i++) {

                    //Get a random demo items
                    $randItem = DemoItem::where('vendor_id', $receipt->vendor->id)->orderByRaw("RAND()")->with('category')->first();

                    if ($randItem == null || array_search($randItem->name, $usedItemNames) !== false) {
                        continue;
                    }

                    $usedItemNames[] = $randItem->name;

                    //store into receipt item
                    $unitprice = (!empty($randItem->price) ? $randItem->price : round((((float)rand(100, 2000)) / 100), 2));
                    $qty = rand(1, 5);
                    $subtotal = round(($qty * $unitprice), 2);

                    $item = Item::create(array('name' => $randItem->name, 'quantity' => $qty, 'unit_price' => $unitprice,
                        'subtotal' => $subtotal, 'discount' => '{"discount":false}'));
                    $item->category()->associate($randItem->category);
                    $item->receipt()->associate($receipt);
                    $item->user()->associate(Auth::user());
                    $item->time = $receipt->time;
                    $item->unit = (empty($randItem->unit)?"pc":$randItem->unit);

                    $total += $subtotal;

                    $item->save();

                    $data['items'][] = array("name" => $item->name, "category" => $item->category->name, "quantity" => $qty,
                        "unit" => $item->unit, "unitPrice" => $unitprice, "discount" => array("discount" => false), "subtotal" => $subtotal);

                }


                $receipt->total = $total;

                $data["tax"] = array("taxable" => round($total / 1.2, 2) , "taxRate" => 0.20, "vat" => round($total * 0.2, 2));

                $tendered = round(($total + rand(0, 20)), -1, PHP_ROUND_HALF_UP);
                $data["payments"] = array("totalPayable" => $total, "totalPaid" => $total,
                    "methods" => array(array("method" => "cash", "amount" => $total,
                        "info" => array("tendered" => $tendered, "change" => ($tendered - $total)))));


                $data["loyalty"] = array("scheme" => false);
                $data["vouchers"] = array();
                $data["misc"] = "Thank you for shopping at " . $receipt->vendor->name;

                $receipt->data = json_encode($data, JSON_NUMERIC_CHECK);
            } while (count($data['items']) == 0 && $tries < 3);


            $receipt->save();

        }

        return view("POS.index", ["vendor" => $receipt->vendor->name, "price" => $receipt->total]);
    }


    public function send(Request $request) {

        //Find last generated receipt and trigger nfcpy to send this uuid


        return view("POS.send");
    }

    public function check(Request $request) {

        //cehck nfcpy results
        try {
            $receipt = Receipt::where('status', 'new')->first();

            if ($receipt == null) {
                return response()->json(['completed' => true]);
            }

            return response()->json(['completed' => false]);
        } catch (ModelNotFoundException $exp) {
            return response()->json(['completed' => true]);
        }

    }


}
