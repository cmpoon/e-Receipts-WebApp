@extends('layouts.master')

@section('javascript')
    <script src="/assets/js/jsbarcode.min.js"></script>

    <script>
        $(document).ready(function() {
            $("#date-field").change(function () {
                $.ajax({

                    type: "GET",
                    url: '{{ action('SearchController@getDate')  }}',
                    data: "date=" + $("#date-field").val(), // appears as $_GET['id'] @ your backend side
                    success: function (data) {
                        // data is ur summary
                        $('#resultlist').html(data);

                        $( "#resultlist" ).listview( "refresh" );
                    }

                });
            });
        });

    </script>

    @endsection

@section('content')
<form class="ui-filterable">
<div class="ui-field-contain">
<label for="search-basic">Search:</label>
<input id="filterBasic-input" data-type="search" placeholder="Search by name..." id="date-field">
</div>
<div class="ui-field-contain">
<label for="date-1">Date:</label>
<input type="date" name="date-1" id="date-field" placeholder="Search by date..." >
</div>
</form>

<ul data-role="listview" data-inset="true" data-filter="true" data-input="#filterBasic-input" id="resultlist">
@include('search.day',$days)
</ul>
</form>
@endsection


	

