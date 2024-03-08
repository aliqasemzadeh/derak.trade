<?php

namespace App\Http\Controllers;

use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $activeTokens = config('tokens.active');
        $priceTokens = [];
        foreach ($activeTokens as $token) {
            $lastPrice = Price::where('token', $token)->orderby('created_at', 'desc')->latest()->first();
            $priceTokens[$token] = $lastPrice->price;
        }
        return view('home.index', compact( 'priceTokens'));
    }

    public function token($token): View
    {
        $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(30)->latest()->get();
        return view('home.token', compact( 'prices', 'token'));
    }

    public function tokenChartData($token)
    {
        $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(30)->latest()->get();
        $data = [];
        foreach ($prices as $price) {
            $data['prices'][] = $price->price;
            $data['times'][] = $price->created_at;
        }
        return response()->json($data);
    }
}
