<li><a href="{{ action('VoucherController@getVoucher', ['id' => $voucher->id ]) }}">
        <p><strong>{{ $voucher->name }}</strong></p>
        <p>{{ $voucher->details }}<p>
        <p class="ui-li-aside"><strong>{{ $voucher->expiration }}</strong></p>
    </a></li>