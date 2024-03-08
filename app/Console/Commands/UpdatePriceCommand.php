<?php

namespace App\Console\Commands;

use App\Models\Price;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdatePriceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'derak:update-price {token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $token = $this->argument('token');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'http://176.9.35.126/~getformi/?token=' . $token,
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
        $lastPrice = Price::where('token', $token)->orderby('created_at', 'desc')->latest()->first();
        $data['price'] = "4.0001";
        echo $lastPrice->price . "\n";
        echo $data['price'] . "\n";
        echo strcmp($lastPrice->price, $data['price']) . "\n";

        if(strcmp($lastPrice->price, $data['price']) !== 0) {
            /*Price::create([
                'token' => $token,
                'price' => $data['price'],
                'change' => $data['24h_change'],
            ]);*/
            echo "Insert!\n";

            $prices = Price::where('token', $token)->orderby('created_at', 'desc')->limit(150)->latest()->get();
            $oldData = [];
            foreach ($prices as $price) {
                $oldData['prices'][] = $price->price;
                $oldData['times'][] = $price->created_at;
            }
            $max = max($oldData['prices']);
            $min = min($oldData['prices']);

            if(bccomp($data['price'], $max, 5)  === 1) {
                echo "New Max\n";
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
                    CURLOPT_POSTFIELDS => array('gateway' => env('APP_SMS_GATEWAY'),'to' => env('APP_TEST_MOBILE'),'text' => "قیمت بالا" ."\n". $token ."\n".$data['price']."\n"."لغو11"),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
            }

            if(bccomp($data['price'], $min, 5) === -1) {
                echo "New Min\n";
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
                    CURLOPT_POSTFIELDS => array('gateway' => env('APP_SMS_GATEWAY'),'to' => env('APP_TEST_MOBILE'),'text' => "قیمت پایین"."\n" . $token ."\n".$data['price']."\n"."لغو11"),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
            }
        }
    }
}
