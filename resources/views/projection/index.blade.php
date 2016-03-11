@extends('layouts.master')

@section('javascript')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script src="/assets/js/highcharts.theme.js"></script>
    <script>
        $(function () {


            $('#category').change(function () {
                console.log("Changing Category");
                //document.getElementById("changeform").submit();
                $('#changeform').submit();
            });


            $('#container').highcharts({
                credits:{
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



    <div class="ui-corner-all custom-corners">
        <div class="ui-bar ui-bar-b">
            <label for="amount"><h3>View Specific Category</h3></label>
        </div>
        <div class="ui-body ui-body-b">
            @if (session('status'))
                <p>{{ session('status') }}</p>
            @endif


            <div class="ui-field-contain">
                <form action="{{ action('ProjectionController@getIndex') }}" method="get" data-ajax="false"
                      id="changeform">
                    <select name="category" id="category">
                        @foreach($categories as $cateEntry)
                            <option value="{{$cateEntry['id']}}"
                                    @if($cateEntry['selected'])selected="selected"@endif>{{  $cateEntry['name'] }}</option>
                        @endforeach
                    </select>
                    <input type="submit" id="submit" class="ui-shadow ui-btn ui-corner-all ui-mini" value="Go"/>

                </form>
            </div>
        </div>

    </div>

@endsection