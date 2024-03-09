<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MainController extends Controller
{
    public function index()
    {
        $activeTokens = config('tokens.active');
        $priceTokens = [];
        foreach ($activeTokens as $token) {
            $lastPrice = Price::where('token', $token)->orderby('created_at', 'desc')->latest()->first();
            $priceTokens[$token] = $lastPrice->price;
        }
        return response()->json($priceTokens);
    }

    public function token($token)
    {
        $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(150)->latest()->get();
        $data = [];
        foreach ($prices as $price) {
            $data['prices'][] = $price->price;
            $data['times'][] = $price->created_at;
        }
        return response()->json($data);
    }
}
