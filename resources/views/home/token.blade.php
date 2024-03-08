@extends('layouts.app')

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/axios@1.6.7/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('tokenDataChart');
        axios.get('{{ route('home.token-data-chart', [$token]) }}').then(function (response) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    datasets: [
                        {
                            label: "Prices",
                            data: response.data,
                            borderColor: '#0936ca',
                            backgroundColor: '#041631',
                            fill: false
                        },
                    ]
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
