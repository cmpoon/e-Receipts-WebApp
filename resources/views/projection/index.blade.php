@extends('layouts.master')

@section('javascript')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="/assets/js/highcharts.theme.js"></script>
    <script>
        $(function () {
            $('#container').highcharts({
                credit: {
                    enabled: false
                },
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Monthly Projection Chart',
                },
                subtitle: {
                    text: '{{ $month }}',
                },
                xAxis: {
                    type: 'datetime',
                    dateTimeLabelFormats: { // don't display the dummy year
                        month: '%e. %b',
                        year: '%b'
                    },
                    title: {
                        text: 'Date'
                    },
                    plotBands: [{ // Light air
                        from: Date.UTC({{ $start }}),
                        to: Date.UTC({{ $now }}),
                        color: 'rgba(0, 0, 0, 0)',
                        label: {
                            text: 'Past',
                            style: {
                                color: '#F0F0F0'
                            }
                        }
                    }, { // Light breeze
                        from: Date.UTC({{ $now }}),
                        to: Date.UTC({{ $end }}),
                        color: 'rgba(68, 170, 213, 0.1)',
                        label: {
                            text: 'Future',
                            style: {
                                color: '#F0F0F0'
                            }
                        }
                    }]
                },
                yAxis: {
                    title: {
                        text: 'Money £',
                    },
                    min: 0
                },

                plotOptions: {
                    line: {
                        enableMouseTracking: false
                    }
                },
                tooltip: {
                    headerFormat: '<b>{series.name}</b><br>',
                    pointFormat: '{point.x:%e. %b}<br/> £{point.y:.2f}'
                },

                series: [{
                    name: 'Projection',
                    data: [
                            @foreach($projection as $date=>$projPoint)
                                   [Date.UTC({{ $date }}), {{ $projPoint }} ],
                        @endforeach
                ]
                },
                    {
                        name: 'Spent',
                        data: [
                                @foreach($days as $date=>$point)
                                       [Date.UTC({{ $date }}), {{ $point }} ],
                            @endforeach
                    ]
                    },
                    {
                        name: 'Budget',
                        data: [
                                @foreach($budget as $date=>$point)
                                       [Date.UTC({{ $date }}), {{ $point }} ],
                            @endforeach
                    ]
                    }]
            });
        });
    </script><!-- /content -->
@endsection

@section('content')
    <div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
    </div>
@endsection