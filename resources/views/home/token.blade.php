@extends('layouts.app')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        var option = {
            responsive: true,
            scales: {
                yAxes:[{
                    display:false,
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
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y-2 divide-gray-200 bg-white text-sm">
        <tbody class="divide-y divide-gray-200">
        <thead class="ltr:text-left rtl:text-right">
        <tr>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ __('Price') }}</th>
            <th class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ __('Time') }}</th>
        </tr>
        </thead>

        @foreach($prices as $price)
            <tr>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">${{ $price->price }}</td>
                <td class="whitespace-nowrap px-4 py-2 font-medium text-gray-900">{{ $price->created_at }}</td>

            </tr>
        @endforeach
        </tbody>
    </table>
    </div>
@endsection
