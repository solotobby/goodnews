<?php

namespace App\Http\Controllers;

use App\Models\SmeData;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;

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
}
