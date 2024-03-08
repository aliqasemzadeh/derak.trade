@extends('layouts.app')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        var option = {
            responsive: true,
            scales: {
                yAxes:[{
                    stacked:true,
                    gridLines: {
                        display:true,
                        color:"rgba(255,99,132,0.2)"
                    }
                }],
                xAxes:[{
                    gridLines: {
                        display:true
                    }
                }]
            }
        };
        const ctx = document.getElementById('tokenDataChart').getContext("2d");
        var chartData = {{ \Illuminate\Support\Js::from(array_reverse($data['prices'])) }};
        var wbtcChartData = {{ \Illuminate\Support\Js::from(array_reverse($wbtcData['prices'])) }};
        var chartlabels = {{ \Illuminate\Support\Js::from(array_reverse($data['times'])) }};
            new Chart(ctx, {
                type: 'line',

                data: {
                    labels: chartlabels,
                    datasets: [
                        {
                            label: "{{ $token }}",
                            data:  chartData,
                            borderColor: '#0936ca',
                            backgroundColor: '#0936ca',
                            fill: false
                        }
                    ]
                },
                options: option
            });
    </script>
@endsection

@section('content')
    <div class="flex flex-row">
        <div class="basis-1/2 text-center text-red-900 ring-2 ring-red-500 rounded-lg p-2 m-2 hover:drop-shadow-md">Min:${{ min($data['prices']) }}</div>
        <div class="basis-1/2 text-center text-green-900 ring-2 ring-green-500 rounded-lg p-2 m-2 hover:drop-shadow-md">Max:${{ max($data['prices']) }}</div>
    </div>
    <div>
        <canvas id="tokenDataChart"></canvas>
    </div>

    @foreach($prices as $price)
        ${{ $price->price }} ({{ $price->created_at }})
        <br />
    @endforeach
@endsection
