@extends('layouts.app')

@section('content')
    @foreach($priceTokens as $token => $price)
        <a href="{{ route('home.token', [$token]) }}">{{ $token }}=>
            ${{ $price }}</a>
        <br />
    @endforeach
@endsection
