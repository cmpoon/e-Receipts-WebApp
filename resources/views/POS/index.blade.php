@extends('layouts.focus')

@section('content')
    <div class="title"><p>Payment Successful</p>
    </div>

    <div class="receipt">
        <table>
            <tr><th>Item</th><th>Qty</th><th>&pound</th></tr>

        </table>

<p>{{ $receipt->data  }}</p>

    </div>

    <a href="{{ url('pos/send') }}" class="btn btn-info" >Collect Receipt</a>

@endsection