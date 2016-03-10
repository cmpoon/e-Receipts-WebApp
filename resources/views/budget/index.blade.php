@extends('layouts.master')

@section('javascript')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="/assets/js/highcharts.theme.js"></script>
    <script>
        $(function () {


                // Build the chart
                $('#container').highcharts({
                    credits:{
                        enabled: false
                    },
                    chart: {
                        plotBackgroundColor: null,
                        plotBorderWidth: null,
                        plotShadow: false,
                        type: 'pie'
                    },
                    title: {
                        text: 'Monthly Spending'
                    },
                    subtitle: {
                        text: '{{ $subtitle  }}'
                    },
                    tooltip: {
                        pointFormat: "£<b>{point.y:.2f}</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',

                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b><br/>£{point.y:.2f}',
                                style: {
                                    color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'white'
                                }
                            },
                            showInLegend: true
                        },
                        series: {
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function () {
                                        location.href = this.options.url;
                                    }
                                }
                            }
                        }
                    },

                    series: [{
                        name: 'Spend',
                        colorByPoint: true,

                        data:[@each('budget.JScategory',$categories, 'category')]
                        }
                    ]

                });
            });
    </script>
@endsection

@section('content')
    <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 20px auto"></div>

    <div class="ui-corner-all custom-corners">
        <div class="ui-bar ui-bar-b">
            <label for="amount"><h3>Total Budget</h3></label>
        </div>
        <div class="ui-body ui-body-b">
            @if (session('status'))
                <p>{{ session('status') }}</p>
            @endif


            <div class="ui-field-contain">
                <form action="{{ action('BudgetController@postBudget')  }}" method="post" data-ajax="false">
                    {{ csrf_field() }}
                    <input type="number" name="amount" id="amount" min="0.00" step="0.01" value="{{ $budget  }}" placeholder="Enter a budget...">

                    <input type="submit" id="submit" class="ui-shadow ui-btn ui-corner-all ui-mini" value="Set" />

                </form>
            </div>
        </div>

    </div>
@endsection

@section('footer')
    <p style="text-align:center">You have spent &pound; {{ $grandtotal }} this month
        @unless($budget == "")
            , {{ $percentage }}% of your total budget
        @endunless!</p>
@endsection