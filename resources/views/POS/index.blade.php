@extends('layouts.focus')

@section('header')
    @parent

    <script>

        var polling = true;
        // Shorthand for $( document ).ready()
        $(document).ready(function() {
            (function poll() {

                if (polling) {
                    setTimeout(function () {

                        if (polling) {
                            $.ajax({
                                url: "{{ url('/pos/check') }}",
                                type: "GET",
                                success: function (data) {

                                    if (data.completed) {
                                        polling = false;
                                        console.log("polling: " + data.completed);

                                        document.getElementById('instruction').innerHTML = "<p>Successful</p>";
                                        document.getElementById('loadimg').src = "/assets/images/tick.png";
                                        document.getElementById('loadimg').style.width = "300px";
                                        document.getElementById('loadimg').style.height = "300px";
                                        document.getElementById('next').style.display = "block";
                                        document.getElementById('receipt').style.display = "none";
                                    }
                                },
                                dataType: "json",
                                complete: poll,
                                timeout: 1000
                            })
                        }
                    }, 500); // <-- should be here instead
                }

            })();
        });

    </script>

    <style>
        .receipt{
            font-size: 3em;
        }
    </style>

@endsection

@section('content')
    <div class="title" id="instruction"><p>Payment Successful</p>
        <p>Tap Phone Now</p>
    </div>
    <div class="title"> <img src="/assets/images/load.gif" alt="" id="loadimg" /></div>

    <div class="receipt" id="receipt">
        <p>{{ $vendor }} - &pound;{{ $price }}</p>
        <p></p>
    </div>

    <a href="{{ url('pos') }}" class="btn btn-info" style="display:none;" id="next">Next Receipt</a>

@endsection