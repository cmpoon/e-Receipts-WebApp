@extends("layouts.master")

@section('javascript')
    <script src="/assets/js/jsbarcode.min.js"></script>
    <script>
        $(function () {
            JsBarcode("#barcode", "{{ $voucher->uuid }}", {
                height: 30,
                background: "#333333",
                lineColor: "#fff"
            });
        });
    </script>
@endsection

@section('content')
    <a href="{{  action('VoucherController@getIndex') }}"
       class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ">Back</a>
    <a href="{{  action('VoucherController@getUse', ['id' => $voucher->id]) }}"
       class="ui-btn ui-mini ui-corner-all ui-btn-right ui-icon-check ui-btn-icon-left">Mark as Used</a>


    <h4 style="text-align:center;padding:0px;margin:0px;"><strong>{{ $voucher->vendor->name }}</strong></h4>
    <h4 style="text-align:center;padding:5px;margin:0px;"><strong>{{ $voucher->name }}</strong></h4>

    <p> Voucher Code<br/><img id="barcode" alt="{{ $voucher->uuid }}"/></p>

    <p>{{ $voucher->details }}</p>

    <p style="text-align:center;padding-top:0px;padding-bottom:30px;margin:0px;">
        Expires: {{ $voucher->expiration }}
    </p>
@endsection


