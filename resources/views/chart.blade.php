@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-body">
                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif
                        <canvas id="canvas"></canvas>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        var timeFormat = 'MM/DD/YYYY HH:mm';

        //		function newDate(days) {
        //			return moment().add(days, 'd').toDate();
        //		}
        //
        //		function newDateString(days) {
        //			return moment().add(days, 'd').format(timeFormat);
        //		}
        //
        //		function newTimestamp(days) {
        //			return moment().add(days, 'd').unix();
        //		}
        console.log({{$times}});
        var color = Chart.helpers.color;
        var config = {
            type: 'line',
            data: {
                labels: {{$times}},
                datasets: [{
                    label: "Kimch Premium Chart",
                    backgroundColor: color(window.chartColors.red).alpha(0.5).rgbString(),
                    borderColor: window.chartColors.red,
                    fill: false,
                    data: {{$margins}},
                }]
            },
            options: {
                elements:{
                    line:{
                        tension:0,
                    }
                },
                title:{
                    text: "Chart.js Time Scale"
                },
                scales: {
                    xAxes: [{
                        type: "time",
                        time: {
                            format: timeFormat,
                            // round: 'day'
                            tooltipFormat: 'll HH:mm'
                        },
                        scaleLabel: {
                            display: true,
                            labelString: 'Date'
                        }
                    }, ],
                    yAxes: [{
                        scaleLabel: {
                            display: true,
                            labelString: 'value'
                        }
                    }]
                },
            }
        };

        window.onload = function() {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);

        };

    </script>
@endsection