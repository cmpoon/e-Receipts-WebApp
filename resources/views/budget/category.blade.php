@extends('layouts.master')

@section('javascript')	
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="/assets/js/highcharts.theme.js"></script>
<script>
    $(function () {
        $('#container').highcharts({
            credits:{
                enabled: false
            },
            chart: {
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false,
                type: 'pie',
            },
            title: {
                text: '{{ ucfirst($category->name) }} Spending Breakdown {{ $subtitle  }}'
            },
            subtitle: {
                text: '{{ $percentage }}%<br/>Used'/*,
                style:{fontSize:"2em"
                },
                align: 'center',
                verticalAlign: 'middle',
                y: 25*/
            },

            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br/>£ {point.y:.0f}',
                        style: {
                            color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'white'
                        }
                    },
                    enableMouseTracking:false
                }
            },

            series: [{
                type: 'pie',
                innerSize: '50%',
                data: [
                   @foreach($vendors as $name => $spent)
                        {
                            name:'{{ $name }}',
                            y: {{ $spent }}
                        },
                   @endforeach
                       /* {name:'Left to Spend',
                        y: {{ ($grandtotal - $spend) }} } */
                ]
            }]
        });
    });
</script>
@endsection

@section('content')
<a href="{{ action('BudgetController@getIndex') }}" style="margin-top:8px" data-ajax="false" class="ui-btn ui-shadow ui-corner-all ui-icon-arrow-l ui-btn-icon-notext ">Back</a>
<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px auto"></div>

<div class="ui-corner-all custom-corners">
    <div class="ui-bar ui-bar-b">
        <label for="amount"><h3>Budget for {{ ucfirst($category->name)  }}</h3></label>
    </div>
    <div class="ui-body ui-body-b">
@if (session('status'))
            <p>{{ session('status') }}</p>
@endif


    <div class="ui-field-contain">
        <form action="{{ action('BudgetController@postBudget')  }}" method="post" data-ajax="false">
            {{ csrf_field() }}
        <input type="number" name="amount" pattern="[0-9]*" id="amount" value="{{ $budget  }}" placeholder="Enter a budget...">
            <input type="hidden" name="id" value="{{ $category->id  }}">

            <input type="submit" id="submit" class="ui-shadow ui-btn ui-corner-all ui-mini" value="Set" />

        </form>
    </div>
</div>

</div>
@endsection

@section('footer')
<p style="text-align:center">You have spent &pound; {{ $spend }} this month
@unless($budget == "")
    , {{ $percentage }}% of your budget
    @endunless!
</p>
@endsection
