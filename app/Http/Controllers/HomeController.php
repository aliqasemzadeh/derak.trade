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
            $priceTokens[$token]['price'] = $lastPrice->price;
            $priceTokens[$token]['change'] = $lastPrice->change;
        }
        return view('home.index', compact( 'priceTokens'));
    }

    public function token($token): View
    {
        $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(config('tokens.range'))->latest()->get();
        $data = [];
        foreach ($prices as $price) {
            $data['prices'][] = $price->price;
            $data['times'][] = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $price->created_at)->format('H:i:s');
        }

        $wbtcPrices = Price::where('token', 'WBTC')->orderby('created_at', 'desc')->limit(config('tokens.range'))->latest()->get();
        $wbtcData = [];
        foreach ($wbtcPrices as $price) {
            $wbtcData['prices'][] = $price->price;
            $wbtcData['times'][] = $price->created_at;
        }

        $tokenMax =  max($data['prices']);

        $wbtcMin =  min($wbtcData['prices']);
        $wbtcMax =  min($wbtcData['prices']);
        foreach ($wbtcData['prices'] as $key => $price) {
            $wbtcData['prices'][$key] =  ($wbtcData['prices'][$key] * $tokenMax) / $wbtcMax;
        }

        return view('home.token', compact( 'prices', 'token', 'data', 'wbtcData'));
    }

    public function tokenChartData($token)
    {
        $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(config('tokens.range'))->latest()->get();
        $data = [];
        foreach ($prices as $price) {
            $data['prices'][] = $price->price;
            $data['times'][] = $price->created_at;
        }
        return response()->json($data);
    }
}
