<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use App\Models\SmeData;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function createSME_Data()
    {
        $smeData = SmeData::all();
        return view('admin.create_sme', ['smeData' => $smeData]);
    }

    public function storeSME_Data(Request $request)
    {
        SmeData::create($request->all());
        return back()->with('success', 'Created Successfully');
    }

    public function userList()
    {
        $users = User::role('user')->with('wallet')->get();
        return view('admin.user_list', ['users' => $users]);
    }

    public function fundWallet(Request $request)
    {
        if(isset($request))
        {
            $getWallet = Wallet::where('user_id', $request->user_id)->first();
            $getWallet->balance += $request->amount;
            $getWallet->save();

            Transaction::create([
                'user_id' => $request->user_id,
                'transaction_ref' => 'GN'.time().\Str::random(19),
                'amount' => $request->amount,
                'app_fee' => '0.0',
                'amount_settled' => $request->amount,
                'currency' => 'NGN',
                'transaction_type' => 'direct-top-up',
                'payment_type' => 'credit',
                'status' => 'successful'
            ]);

            return back()->with('success', 'Wallet Credited Successfully');
        }
    }

    public function userTransaction($id)
    {
        $userTransaction = Transaction::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        return view('admin.user_transaction', ['transactions' => $userTransaction ]);
    }

    public function transactionList()
    {
        $transactionList = Transaction::orderBy('created_at', 'desc')->get();
        return view('admin.transaction', ['transactions' => $transactionList ]);
    }

    public function activate($id)
    {
        $user = User::where('id', $id)->first();
        if($user->status == "active")
        {
            $user->status = 'blacklisted';
            $user->save();
            return back()->with('success', 'User Blacklisted');
        }else{
            $user->status = 'active';
            $user->save();
            return back()->with('success', 'User Activated');
        }

    }

    public function queueList()
    {
        $queue = Queue::orderBy('created_at', 'desc')->get();
        return view('admin.queue_list', ['queues' => $queue]);
    }

    public function validateQueue($id)
    {
        $queque = Queue::where('id', $id)->first();

         $queque->payload;//->phone;

        $array_encode = json_encode($queque->payload, true);
        $array_decode = json_decode($array_encode);

        // return  [$array_decode[0], $array_decode[1]];

        if($queque->type == 'sme-databundle'){
           $queque->status = 'completed';
           $queque->save();
           Transaction::create([
            'user_id' => auth()->user()->id,
            'transaction_ref' => \Str::random(10),
            'amount' => $queque->amount,
            'app_fee' => 0.0,
            'amount_settled' => $queque->amount,
            'currency' => "NGN",
            'transaction_type' => 'sme-databundle',
            'payment_type' => 'debit',
            'status' => 'successful',
            'phone' =>'',//$request->phone,
            'network' => NULL
        ]);
           return back()->with('success', 'Transaction completed');
        }elseif($queque->type == 'databundle'){

            if($this->checkBalance()  <= $queque->amount){
                return back()->with('error', 'no enough money in flutterwave account'); 
            }



        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/bills', $array_decode)->throw();

        
        
        if($res['status'] == 'success' ){
           $queque->status = 'completed';
           $queque->save();

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
            $wallet = Wallet::where('user_id', $queque->user_id)->first();
            $wallet->balance += $queque->amount; ///credit wallet
            $wallet->save();

            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' =>  \Str::random(10),
                'amount' => $queque->amount,//$res['data']['amount'],
                'app_fee' => 0.0,
                'amount_settled' => $queque->amount,
                'currency' => "NGN",
                'transaction_type' => 'databundle-reversal',
                'payment_type' => 'credit',
                'status' => 'Failed'
            ]);

            return back()->with('success', 'Error occoured while purchasing data, please try again later');
        }


        }elseif($queque->type == 'airtime'){

        // if($this->checkBalance()  <= $queque->amount){
        //     return back()->with('error', 'no enough money in flutterwave account'); 
        // }

        $new = explode(' ',$array_decode);

        // $array = json_encode($new);

        // return $new_Array = json_decode($array);

        // str_split($array_decode, 1);

        // $array=[];
        // foreach($array_decode as $key => $value)
        // {
        //     $array=[$key=>$value];
        // }

        // return $array;


        return $array_decode;



        return $res = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        // $new = explode(' ',$array_decode);
        ])->post('https://api.flutterwave.com/v3/bills', $array_decode)->throw();


        if( $res['status'] == 'success'){

            $queque->status = 'completed';
            $queque->save();
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
            $wallet = Wallet::where('user_id', $queque->user_id)->first();
            $wallet->balance += $queque->amount; ///credit wallet
            $wallet->save();
            Transaction::create([
                'user_id' => auth()->user()->id,
                'transaction_ref' =>  \Str::random(10), //$res['flw_ref'],
                'amount' =>$queque->amount,
                'app_fee' => 0.0,
                'amount_settled' => $queque->amount,
                'currency' => "NGN",
                'transaction_type' => 'airtime-reversal',
                'payment_type' => 'credit',
                'status' => 'Failed'
            ]);
            return back()->with('error', 'Airtime could not be processed, please try again later');
        }


        }else{
            return back()->with('error', 'nothing to show');
        }

    }


    public function sendNotification($message)
    {
        $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ])->post('https://api.ng.termii.com/api/sms/send', [
            "to"=> '2348150773992',//$number,
            "from"=> "GOODNEWS",
            "sms"=> $message,
            "type"=> "plain",
            "channel"=> "generic",
            "api_key"=> env('TERMI_KEY')
        ]);

         return json_decode($res->getBody()->getContents(), true);
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
}
