<?php

namespace App\Http\Controllers;

use App\Helper\flutterWaveHelper;
use app\Helper\newWave;
use App\Models\Transaction;
use App\Models\Wallet;
use GoodNews\Flutterwave\flutterWaveHelper as FlutterwaveFlutterWaveHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stringable;

class UserController extends Controller
{
    public function topup()
    {

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/banks/NG')->throw();
        return $res;


    }

    public function transaction()
    {
        $url = request()->fullUrl();
        $url_components = parse_url($url);
        parse_str($url_components['query'], $params);
        $status = $params['status'];
        if($status == 'cancelled')
        {
            // return redirect('home');
           return back()->with('error', 'Transaction Cancelled, please try again'); 
        }elseif($status == 'successful')
        {
            $trans_ref =  $params['tx_ref'];
            $trans_id = $params['transaction_id'];

            $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/transactions/'.$trans_id.'/verify')->throw();
        
            $amountSettled = $res['data']['amount_settled'];
            $amountCharged = $res['data']['amount'];
            $ref = $res['data']['flw_ref'];
            $appFee = $res['data']['app_fee'];
            $tranStatus = $res['data']['status'];
            $currency = $res['data']['currency'];
            $paymentType = $res['data']['payment_type'];
            
            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance += $amountSettled;
            $wallet->save();

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' => $ref,
                'amount' => $amountCharged,
                'app_fee' => $appFee,
                'amount_settled' => $amountSettled,
                'currency' => $currency,
                'transaction_type' => 'top-up',
                'payment_type' => $paymentType,
                'status' => $tranStatus
            ]);

            return back()->with('success', 'Top Up Successful');
        }else{

            return back()->with('error', 'An error occoured whole processing'); 
        }
    }

    public function buyAirtime(Request $request)
    {
        $payload = [
            "biller_name" => $request->name,
            "amount" => $request->amount,
            "country" => "NG",
            "customer" => $request->phone,
            "recurrence" => "ONCE",
            "type" => "AIRTIME",
            "reference" => \Str::random(10)
        ];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com//v3/bills', $payload)->throw();

        return $res['status'];

        //return json_decode($res->getBody()->getContents(), true);
        // return $this->sendAirtime($payload, $request->name, $request->amount, $request->customer);
    }

    public function airtime()
    {
        $airtime = $this->billAirtimeCategories()['data'];

        return view('user.airtime', ['airtime' => $airtime]);

       
    }

    public function billAirtimeCategories()
    {
        //https://api.flutterwave.com/v3/bill-categories
        // https://api.flutterwave.com//v3/bills
        // https://api.flutterwave.com/v3/bill-categories?airtime=1

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/bill-categories?biller_code=BIL099')->throw();
        
         return $res;
    }

    public function data()
    {
        return 'data';
    }

    
}
