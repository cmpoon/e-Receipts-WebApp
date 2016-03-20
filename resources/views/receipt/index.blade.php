@extends("layouts.master")


@section('javascript')
    <script src="/assets/js/jsbarcode.min.js"></script>
@endsection

@section('content')
    <a href="{{  action('SearchController@getIndex') }}"
       class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ">Back</a>
    <a href="{{  action('SearchController@getDelete', ['id' => $receipt->id]) }}"
       class="ui-btn ui-mini ui-corner-all ui-btn-right ui-icon-back ui-btn-icon-left">Refund</a>
    <h4 style="text-align:center;padding:5px;margin:0px;"><strong>{{ $receipt->time }}</strong></h4>
    <h4 style="text-align:center;padding:0px;margin:0px;"><strong>{{ $receipt->vendor->name }}</strong></h4>
    <p style="text-align:center;padding-top:0px;padding-bottom:30px;margin:0px;font-size: small;">
        {{ $receipt->vendor->address }}<br/>
        VAT #:&nbsp;{{ $receipt->vendor->vat_number}}
    </p>
    <table align="center" style="width:100%; text-align:left">
        <tr>
            <th style="color:Grey;">Item</th>
            <th style="color:Grey;text-align:left; width: 30%">Qty</th>
            <th style="color:Grey;text-align:right">&pound;</th>
        </tr>
        @each('receipt.item',$receipt->items,'item')
        <tr>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th style="color:Grey;" colspan="2">Sub-total</th>
            <th style="color:#fff;text-align:right;font-weight: bold">{{ number_format ($receipt->total/1.2 ,2) }}</th>
        </tr>
        <tr>
            <th style="color:Grey;" colspan="2">VAT</th>
            <th style="color:#fff;text-align:right;font-weight: bold">{{ number_format ($receipt->total*0.2,2) }}</th>
        </tr>
        <tr>
            <th style="color:Grey;" colspan="2">Total</th>
            <th style="color:#fff;text-align:right;font-weight: bold">{{ number_format ($receipt->total,2) }}</th>
        </tr>
    </table>
    <p style="text-align:center;font-size: small; margin-top: 30px;">Receipt ID<br/><img id="barcode-{{ $receipt->uuid }}" alt="{{ $receipt->uuid }}" /></p>


    <script>
            JsBarcode("#barcode-{{ $receipt->uuid }}", "{{ $receipt->uuid }}", {
                height: 20,
                background: "#333333",
                lineColor: "#fff"
            });
    </script>
@endsection
