<li><a href="{{ action('SearchController@getReceipt', ['id' => $receipt->id ]) }}">
<p><strong>&pound{{ $receipt->total }} {{ $receipt->vendor->name }}</strong></p>
<p>{{ $receipt->vendor->address }}<p>
<p class="ui-li-aside"><strong>{{ $receipt->time }}</strong></p>
</a></li>