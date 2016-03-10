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

                                        document.getElementById('instruction').innerHTML = "Completed!";
                                        document.getElementById('loadimg').src = "/assets/images/tick.png";
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

    @endsection


@section('content')
    <div class="title"><p id="instruction">Tap Phone Now</p>
        <img src="/assets/images/load.gif" alt="" id="loadimg" />
    </div>
@endsection