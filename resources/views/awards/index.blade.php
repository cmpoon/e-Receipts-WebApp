@extends('layouts.master')

@section('javascript')
    <style>
        .badge-link,
        .ui-page-theme-a a:visited, html .ui-bar-a a:visited, html .ui-body-a a:visited, html body .ui-group-theme-a a:visited,
        .ui-page-theme-a a:hover, html .ui-bar-a a:hover, html .ui-body-a a:hover, html body .ui-group-theme-a a:hover,
        .ui-page-theme-a a, html .ui-bar-a a, html .ui-body-a a, html body .ui-group-theme-a a{
            color: #fff;
            font-style: normal;
            text-decoration: none;
        }
        .badge-link img{
            width:150px;
        }
    </style>
@endsection

@section('content')
    <p>Earn awards from being a good shopper this week and compare against your friends!</p>

    <div class="ui-grid-a" style="text-align:center">
        <div class="ui-block-a">
            <a href="#popup-bigsaverday" data-rel="popup" class="badge-link">
                <img src="/assets/images/badges/bigsaverday-{{ $saver ? 'on' : 'off'  }}.png" alt="" />
                <p><strong>Big Saver</strong></p></a>
        </div>
        <div class="ui-block-b">
            <a href="#popup-cheapshopperweek" data-rel="popup" class="badge-link">
                <img src="/assets/images/badges/cheapshopperweek-{{ $cheapshopper ? 'on' : 'off'  }}.png" alt="" />
                <p><strong>Cheap Shopper</strong></p>
            </a>
        </div>
        <div class="ui-block-a">
            <a href="#popup-crazycollector" data-rel="popup" class="badge-link">
                <img src="/assets/images/badges/crazycollector-{{ $crazycollector ? 'on' : 'off'  }}.png" alt="" />
                <p><strong>Crazy Collector</strong></p>
            </a>
        </div>
        <div class="ui-block-b">

            <a href="#popup-projectionperfection" data-rel="popup" class="badge-link">
                <img src="/assets/images/badges/projectionperfection-{{ $projectionperfection ? 'on' : 'off'  }}.png"
                     alt="" />
                <p><strong>Projection Perfection</strong></p>
            </a>
        </div>
    </div>

    <div data-role="popup" id="popup-bigsaverday">
        <p>You earn Big Saver when you use all your expiring vouchers this week.</p>
    </div>
    <div data-role="popup" id="popup-cheapshopperweek">
        <p>You earn Cheap Shopper when you stay under your grocery food shopping budget.</p>
    </div>
    <div data-role="popup" id="popup-crazycollector">
        <p>You earn Crazy Collector when you collect over 50 receipts in a month.</p>
    </div>
    <div data-role="popup" id="popup-projectionperfection">
        <p>You earn Projection Perfection when you keep your monthly projection under your budget.</p>
    </div>
@endsection