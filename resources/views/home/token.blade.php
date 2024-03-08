@extends('layouts.app')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('tokenDataChart').getContext("2d");
        axios.get('{{ route('home.token-data-chart', [$token]) }}').then(function (response) {
            console.log(response.data);
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: response.data.labels,
                    datasets: [
                        {
                            label: "Prices",
                            data: response.data.prices,
                            borderColor: '#0936ca',
                            backgroundColor: '#041631',
                            fill: false
                        },
                    ]
                },
                options: {
                    scales: {
                    }
                }
            });
        }).catch(function (error) {
            console.log(error);
        });
    </script>
@endsection

@section('content')
    <div>
        <canvas id="tokenDataChart" width="400" height="140"></canvas>
    </div>
    @foreach($prices as $price)
        ${{ $price->price }} ({{ $price->created_at }})
        <br />
    @endforeach
@endsection
