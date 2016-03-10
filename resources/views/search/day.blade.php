@forelse ($days as $day)

    <li data-role="list-divider" style="background-color:grey">{{ $day['date'] }} <span class="ui-li-count">{{ $day['count'] }}</span></li>
    @each('search.receipt',$day['receipts'],'receipt')

@empty

    <li><p><strong>No Receipts Found</strong></p></li>

@endforelse