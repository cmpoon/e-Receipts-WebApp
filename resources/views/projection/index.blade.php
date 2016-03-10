@extends('layouts.master')

@section('javascript')
<script src="https://code.highcharts.com/highcharts.js"></script>
@endsection

@section('content')
<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
</div>
<script>
$(function () {
	//'`https://www.highcharts.com/samples/data/jsonp.php?filename=usdeur.json&callback=?'
    $.getJSON('{{ route('json/budget') }}', function (data){
    $('#container').highcharts({
	  chart: {
            backgroundColor: '#f9f9f9',
            type: 'line'
        },
        title: {
            text: 'Monthly Projection Chart',
			style: {
                color: '#FFFFF',
                fontWeight: 'bold'
            },
            x: -20 //center,
			
        },
        subtitle: {
            text: 'March',
            x: -20
        },
        xAxis: {
		
            type:'datetime'
        },
        yAxis: {
            title: {
                text: 'Money Spent(£)',
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
		
       plotOptions:{
	  line:{ enableMouseTracking:false
	   }
	   },
        
        series: [{
                name: 'Monthly projection',
                data: data.projection
            },
			{
                name: 'Actual Money Spent',
                data: data.actual
            },
			{
                name: 'Target',
                data: data.target
            }]
    });
});
});
</script><!-- /content -->
@endsection