@extends(layouts.master)

@section('content')
<div class="ui-grid-a" style="text-align:center">
    <div class="ui-block-a">
	<img src="bigsaverday-{{ $saver ? 'on' : 'off'  }}.png" alt="bigsaverday" style="width:100%">
	<p><strong>Big Saver Day</strong></p>
	</div>
    <div class="ui-block-b">
	<img src="cheapshopperweek-{{ $cheapshopper ? 'on' : 'off'  }}.png" alt="bigsaverday" style="width:100%">
	<p><strong>Cheap Shopper Week</strong></p>
	</div>
	<div class="ui-block-a">
	<img src="crazycollector-{{ $crazycollector ? 'on' : 'off'  }}.png" alt="bigsaverday" style="width:100%">
	<p><strong>Crazy Collector</strong></p>
	</div>
    <div class="ui-block-b">
	<img src="projectionperfection-{{ $projectionperfection ? 'on' : 'off'  }}.png" alt="bigsaverday" style="width:100%">
	<p><strong>Projection Perfection</strong></p>
	</div>
</div>
@endsection