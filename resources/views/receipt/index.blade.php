@extends("layouts.master")

@section('content')
<a href="{{  action('SearchController@getIndex') }}" class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ">Back</a>
<a href="{{  action('SearchController@getDelete', ['id' => $receipt->id]) }}" class="ui-btn ui-mini ui-corner-all ui-btn-right ui-icon-back ui-btn-icon-left">Refund</a>
<h4 style="text-align:center;padding:5px;margin:0px;"><strong>{{ $receipt->time }}</strong></h4>
<h4 style="text-align:center;padding:0px;margin:0px;"><strong>{{ $receipt->vendor->name }}</strong></h4>
<p style="text-align:center;padding-top:0px;padding-bottom:30px;margin:0px;"><small>{{ $receipt->vendor->address }}</small></p>
<p style="text-align:center;padding-top:0px;padding-bottom:30px;margin:0px;"><small>{{ $receipt->vendor->vat_number}}</small></p>
<table align="center" style="width:100%; text-align:left">
 <tr>
  <th style="color:Grey;">Item</th>
 <th style="color:Grey;">Qty</th>
 <th style="color:Grey;text-align:right">&pound;</th>
</tr> 
@each('receipt.item',$receipt->items,'item')
</table>
<p></p>
<table align="center" style="width:100%; text-align:left">

 <th style="color:Grey;width:33%" colspan="2">Total</th>
  <th style="color:#fff;text-align:right;font-weight: bold">&pound;{{ $receipt->total }}</th>
</tr> 
</table> 

<div>BARCODE</div>
@endsection

 
