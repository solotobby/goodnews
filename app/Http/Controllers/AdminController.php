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

        if($queque->type == 'sme-databundle'){
           $queque->status = 'completed';
           $queque->save();
           return back()->with('success', 'Transaction completed');
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
}
