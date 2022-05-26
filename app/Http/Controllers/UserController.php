<?php

namespace App\Http\Controllers;

use App\Helper\flutterWaveHelper;
use app\Helper\newWave;
use app\Http\Controllers\Traits\Capitalsage;
use App\Models\SmeData;
use App\Models\Transaction;
use App\Models\Wallet;
use GoodNews\Flutterwave\flutterWaveHelper as FlutterwaveFlutterWaveHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Stringable;

class UserController extends Controller
{
    //use Capitalsage;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function topup(Request $request)
    {
        //return $request;
        $amount = $request->amount;

        $percent = 1.5 / 100 * $amount; //added to admin wallet

        $amountSent = $amount + $percent;
        $payload = [
            'tx_ref' => \Str::random(10),
            'amount' => $amountSent,
            'currency' => "NGN",
            'redirect_url' => url('transaction'),
            'customer' => [
                'email'=> auth()->user()->email,
                'phonenumber'=> auth()->user()->phone,
                'name' => auth()->user()->name
            ],
        ];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->post('https://api.flutterwave.com/v3/payments', $payload)->throw()->json();
        
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
                ])->get('https://api.flutterwave.com/v3/transactions/'.$trans_id.'/verify')->throw()->json();

                //return json_decode($res->getBody()->getContents(), true);
        
            $amountSettled = $res['data']['amount_settled'];
            $amountCharged = $res['data']['amount'];
            $ref = $res['data']['flw_ref'];
            $appFee = $res['data']['app_fee'];
            $tranStatus = $res['data']['status'];
            $currency = $res['data']['currency'];
            $paymentType = $res['data']['payment_type'];

            $percentOf = 1.45;
            $percentAmount = $percentOf / 100 * $amountSettled;
            $amount_to_be_credited = $amountSettled - $percentAmount;

            $adminWallet = Wallet::where('type', 'admin')->first();
            $adminWallet->balance += $percentAmount;
            $adminWallet->save();
            
            $wallet = Wallet::where('user_id', auth()->user()->id)->first();
            $wallet->balance +=  $amount_to_be_credited; //$amountSettled;
            $wallet->save();

            $transaction = Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' => $ref,
                'amount' => $amount_to_be_credited,
                'app_fee' => $appFee,
                'amount_settled' => $amount_to_be_credited,
                'currency' => $currency,
                'transaction_type' => 'top-up',
                'payment_type' => 'credit', //$paymentType,
                'status' => $tranStatus
            ]);

            return back()->with('success', 'Top Up Successful');
        }else{

            return back()->with('error', 'An error occoured while processing'); 
        }
    }

    public function buyAirtime(Request $request)
    {

        if($this->checkBalance()  <= $request->amount){
            return back()->with('error', 'An error occoured while processing, please try again later'); 
        }

        $checkTransaction = Transaction::where('user_id', auth()->user()->id)->where('transaction_type', 'top-up')->get();
        if(count($checkTransaction) <= 0)
        {
            $message = auth()->user()->name. ' is fucking up with '.auth()->user()->phone;
            $this->sendErrorNotifiaction($message);
            return back()->with('error', 'error');
        }

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance <=  $request->amount)
        {
            return back()->with('error', 'Insurficient fund in your wallet');
        }
        $wallet->balance -= $request->amount; ///debit wallet
        $wallet->save();


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
        ])->post('https://api.flutterwave.com/v3/bills', $payload)->throw();


        if( $res['status'] == 'success'){
            //add the recepient phone number on this table
            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' => $res['data']['flw_ref'],
                'amount' => $res['data']['amount'],
                'app_fee' => 0.0,
                'amount_settled' => $res['data']['amount'],
                'currency' => "NGN",
                'transaction_type' => 'airtime',
                'payment_type' => 'debit',
                'status' => $res['status']
            ]);

            return back()->with('success', 'Airtime purchase successful');

        }else{

            //reverse transaction 

            $wallet->balance += $request->amount; ///credit wallet
            $wallet->save();

            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' =>  \Str::random(10), //$res['flw_ref'],
                'amount' => $request->amount,
                'app_fee' => 0.0,
                'amount_settled' => $request->amount,
                'currency' => "NGN",
                'transaction_type' => 'airtime-reversal',
                'payment_type' => 'credit',
                'status' => 'Failed'
            ]);
            return back()->with('error', 'Airtime could not be processed, please try again later');
        }

           
            return back()->with('success', 'Airtime purchase succesful');
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
        // $token = $this->capitalsageauthorize()['data']['token']['access_token'];
        //$billers = $this->capitalSageFetchData($token)['billers'];

        return view('user.data'); 
    }

    public function getDataBundle($billerCode)
    {
        
    //$token = $this->capitalsageauthorize()['data']['token']['access_token'];
       //return $billerCode;
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/bill-categories?biller_code='.$billerCode)->throw();
        
        $databundle = $res['data'];
         
        return view('user.buydata', ['databundle' => $databundle, 'biller_code' => $billerCode]);
    }

    public function checkBalance()
    {
        //check flutterwave account balance
        return $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/balances/NGN')->throw()['data']['available_balance'];
    }

    public function buyData(Request $request)
    {

        $values = explode(':',$request->name);
        $biller_name = $values['0'];
        $item_code = $values['1'];
        $amount = $values['2'];

        $checkTransaction = Transaction::where('user_id', auth()->user()->id)->where('transaction_type', 'top-up')->get();
        if(count($checkTransaction) <= 0)
        {
            $message = auth()->user()->name. ' is fucking up with '.auth()->user()->phone;
            $this->sendErrorNotifiaction($message);
            return back()->with('error', 'error');
        }

        if($this->checkBalance()  <= $amount){
            return back()->with('error', 'An error occoured while processing, please try again later'); 
        }


        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance <=  $amount)
        {
            return back()->with('error', 'Insurficient fund in your wallet');
        }
        $wallet->balance -= $amount; ///debit wallet
        $wallet->save();
       
        // $token = $this->capitalsageauthorize()['data']['token']['access_token'];
        
        $payload = [
            "type" => $biller_name,
            "reference" => \Str::random(10),
            "country" => 'NG',
            "customer" => $request->phone,
            "amount" => $amount,
            "recurrence" => 'ONCE',
            "biller_name"=> $biller_name
        ];

        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/bills', $payload)->throw();

        
        
        if($res['status'] == 'success' ){
            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' => $res['data']['flw_ref'],
                'amount' => $res['data']['amount'],
                'app_fee' => 0.0,
                'amount_settled' => $res['data']['amount'],
                'currency' => "NGN",
                'transaction_type' => 'databundle',
                'payment_type' => 'debit',
                'status' => $res['data']['status'],
                'phone' => $res['data']['phone_number'],
                'network' => $res['data']['network']
            ]);

            return back()->with('success', 'Data Bundle purchase successful');
        }elseif($res['status'] == 'error'){

            $wallet->balance += $amount; ///credit wallet
            $wallet->save();
            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' =>  \Str::random(10),
                'amount' => $amount,//$res['data']['amount'],
                'app_fee' => 0.0,
                'amount_settled' => $amount,
                'currency' => "NGN",
                'transaction_type' => 'databundle-reversal',
                'payment_type' => 'credit',
                'status' => 'Failed'
            ]);

            return back()->with('success', 'Error occoured while purchasing data, please try again later');
        }
        
    }

    public function SMEData()
    {
        $smeData = SmeData::all();
        return view('user.smedata', ['smeData' => $smeData]);
    }

    public function buySMEData(Request $request)
    {
    
        $values = explode(':',$request->name);
        $gig = $values['0'];
         $amount = $values['1'];

        // if($this->checkBalance()  <= $amount){
        //     return back()->with('error', 'An error occoured while processing, please try again later'); 
        // }

        $checkTransaction = Transaction::where('user_id', auth()->user()->id)->where('transaction_type', 'top-up')->get();
        if(count($checkTransaction) <= 0)
        {
            $message = auth()->user()->name. ' is fucking up with '.auth()->user()->phone;
            $this->sendErrorNotifiaction($message);
            return back()->with('error', 'error');
        }

        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        if($wallet->balance <=  $amount)
        {
            return back()->with('error', 'Insurficient fund in your wallet');
        }
        $wallet->balance -= $amount; ///debit wallet
        $wallet->save();

        Transaction::create([
            'user_id' => auth()->user()->id,
            'transaction_ref' => \Str::random(10),
            'amount' => $amount,
            'app_fee' => 0.0,
            'amount_settled' => $amount,
            'currency' => "NGN",
            'transaction_type' => 'sme-databundle',
            'payment_type' => 'debit',
            'status' => 'successful',
            'phone' =>$request->phone,
            'network' => NULL
        ]);

        //$phone = '234'.substr($score->user->phone, 1);
        $message = "A ".$gig. " GIG SME DATA REQUEST FROM ".$request->phone." AT ".$amount." NGN";
        $this->sendNotification($message);

        return back()->with('success', 'SME Data Bundle is being processed');
    }

    public function sendNotification($message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> '2348150773992',//$number,
            "from"=> "FREEBYZ",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    public function sendErrorNotifiaction($message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> '2348137331282',//$number,
            "from"=> "FREEBYZ",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

         return json_decode($res->getBody()->getContents(), true);
    }

    public function capitalsageauthorize()
    {
        $payload = [
            'email' => 'farohunbi.st@gmail.com',
            'password' => 'Testimonies100#'
        ];
        
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->post('https://sagecloud.ng/api/v2/merchant/authorization', $payload)->throw();
        
         return $res;
    }

    public function capitalSageFetchData($token)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.$token//.env('FL_SECRET_KEY')
         ])->get('https://sagecloud.ng/api/v2/internet/data/fetch-providers')->throw();
        
         return $res;
    }

    
}
