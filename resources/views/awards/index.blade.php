@extends('layouts.master')

@section('content')
    <p>Earn badges from being a good shopper!</p>

    <div class="ui-grid-a" style="text-align:center">
        <div class="ui-block-a">
            <img src="/assets/images/badges/bigsaverday-{{ $saver ? 'on' : 'off'  }}.png" alt="" style="width:150px">

            <p><strong>Big Saver Day</strong></p>
        </div>
        <div class="ui-block-b">
            <img src="/assets/images/badges/cheapshopperweek-{{ $cheapshopper ? 'on' : 'off'  }}.png" alt="" style="width:150px">

            <p><strong>Cheap Shopper Week</strong></p>
        </div>
        <div class="ui-block-a">
            <img src="/assets/images/badges/crazycollector-{{ $crazycollector ? 'on' : 'off'  }}.png" alt="" style="width:150px">

            <p><strong>Crazy Collector</strong></p>
        </div>
        <div class="ui-block-b">
            <img src="/assets/images/badges/projectionperfection-{{ $projectionperfection ? 'on' : 'off'  }}.png" alt=""
                 style="width:150px">

            <p><strong>Projection Perfection</strong></p>
        </div>
    </div>
@endsection