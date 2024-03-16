<?php

namespace App\Jobs;

use App\Models\Price;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UpdatePriceJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $token;

    /**
     * Create a new job instance.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://176.9.35.126/~getformi/?token=' . $this->token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $data = json_decode($response, true);

        $lastPrice = Price::where('token', $this->token)->orderby('created_at', 'desc')->latest()->first();
        if(!$lastPrice) {
            Price::create([
                'token' => $this->token,
                'price' => $data['price'],
                'change' => $data['24h_change'],
            ]);
            $lastPrice = Price::where('token', $this->token)->orderby('created_at', 'desc')->latest()->first();
        }

        if(strcmp($lastPrice->price, $data['price']) !== 0) {
            Price::create([
                'token' => $this->token,
                'price' => $data['price'],
                'change' => $data['24h_change'],
            ]);

            $prices = Price::where('token', $this->token)->orderby('created_at', 'desc')->limit(config('tokens.range'))->latest()->get();
            $oldData = [];
            foreach ($prices as $price) {
                $oldData['prices'][] = $price->price;
                $oldData['times'][] = $price->created_at;
            }
            $max = max($oldData['prices']);
            $min = min($oldData['prices']);

            if(count($oldData['prices']) > 50) {
                if(in_array( $this->token, config('tokens.sms'))) {
                    if($data['price'] * 10**18 > $max * 10**18) {
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.sabanovin.com/v1/'.env('APP_SMS_API').'/sms/send.json',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('gateway' => env('APP_SMS_GATEWAY'),'to' => env('APP_TEST_MOBILE'),'text' => "قیمت بالا" ."\n". $this->token ."\n".$data['price']."\n"."لغو11"),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                    }

                    if($data['price'] * 10**18 < $min * 10**18) {
                        $curl = curl_init();

                        curl_setopt_array($curl, array(
                            CURLOPT_URL => 'https://api.sabanovin.com/v1/'.env('APP_SMS_API').'/sms/send.json',
                            CURLOPT_RETURNTRANSFER => true,
                            CURLOPT_ENCODING => '',
                            CURLOPT_MAXREDIRS => 10,
                            CURLOPT_TIMEOUT => 0,
                            CURLOPT_FOLLOWLOCATION => true,
                            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                            CURLOPT_CUSTOMREQUEST => 'POST',
                            CURLOPT_POSTFIELDS => array('gateway' => env('APP_SMS_GATEWAY'),'to' => env('APP_TEST_MOBILE'),'text' => "قیمت پایین"."\n" . $this->token ."\n".$data['price']."\n"."لغو11"),
                        ));

                        $response = curl_exec($curl);

                        curl_close($curl);
                    }
                }
            }
        }
    }
}
