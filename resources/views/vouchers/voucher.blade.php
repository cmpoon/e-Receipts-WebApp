<li><a href="{{ action('VoucherController@getVoucher', ['id' => $voucher->id ]) }}">
        <p><strong>{{ $voucher->name }}</strong></p>
        <p class="ui-li-aside">Expires {{ $voucher->expiration }}</p>
    </a></li>