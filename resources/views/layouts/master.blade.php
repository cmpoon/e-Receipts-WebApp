<!DOCTYPE html>
<html>
<head>
    <title>receiptBook</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css"/>
    <link rel="stylesheet" href="/assets/themes/e-Receipt.min.css"/>
    <link rel="stylesheet" href="/assets/themes/jquery.mobile.icons.min.css"/>
    <script src="http://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>

    @section('javascript')
    @show
</head>
<body>

<div data-role="page" id="page">

    <div role="main" class="ui-content">
        @yield('content')
    </div>

    <div data-role="footer">
        @section('footer')
        @show
    </div><!-- /footer -->
</div><!-- /page -->

</body>
</html>