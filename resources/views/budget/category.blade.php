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
                text: '{{ ucfirst($category->name) }} Spending Breakdown'
            },
            subtitle: {
                text: '{{ $percentage }}%<br/>Used',
                style:{fontSize:"2em"
                },
                align: 'center',
                verticalAlign: 'middle',
                y: 25
            },

            plotOptions: {
                pie: {
                    dataLabels: {
                        enabled: true,
                        format: '<b>{point.name}</b><br/>GBP {point.y:.0f}',
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
<div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
@endsection

@section('footer')
<p style="text-align:center">You have spent &pound; {{ $spend }} this month</p>
@endsection
