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
                    tooltip: {
                        pointFormat: "GBP <b>{point.y:.2f}</b>"
                    },
                    plotOptions: {
                        pie: {
                            allowPointSelect: true,
                            cursor: 'pointer',

                            dataLabels: {
                                enabled: true,
                                format: '<b>{point.name}</b><br/>GBP {point.y:.2f}',
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
    <div id="container" style="min-width: 310px; height: 400px; max-width: 600px; margin: 0 auto"></div>
@endsection

@section('footer')
    <p style="text-align:center">You have spent &pound; {{ $grandtotal }} this month.</p>
@endsection