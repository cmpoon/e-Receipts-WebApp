@extends('layouts.master')
@section('content')
    <form class="ui-filterable">
        <div class="ui-field-contain">
            <label for="search-basic">Search:</label>
            <input id="filterBasic-input" data-type="search" placeholder="Search by name..." id="date-field">
        </div>
    </form>

    <ul data-role="listview" data-inset="true" data-filter="true" data-input="#filterBasic-input" id="resultlist">
        @forelse ($vendors as $name => $vouchers)

            <li data-role="list-divider" style="background-color:grey">{{ $name }} <span class="ui-li-count">{{ count($vouchers) }}</span></li>
            @each('vouchers.voucher',$vouchers,'vouchers')

        @empty

            <li><p><strong>No Vouchers Found</strong></p></li>

        @endforelse
    </ul>
    </form>
@endsection




