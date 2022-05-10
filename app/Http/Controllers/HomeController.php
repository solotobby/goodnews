<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        if(auth()->user()->hasRole('admin')){
            $adminWallet = Wallet::where('type', 'admin')->first();
            $transactions = Transaction::all();
            return view('admin.home', ['wallet' => $adminWallet, 'transactions' => $transactions]); 
        }
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        $transaction = Transaction::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')->get();

        $monthlyTransaction = Transaction::whereMonth('created_at', Carbon::now()->month)->get();
        $dailyTransaction = Transaction::whereDate('created_at', Carbon::today())->get();
        //$topup = $monthlyTransaction->where('')
        return view('user.home', ['wallet' => $wallet, 'transactions' => $transaction, 'monthly' => $monthlyTransaction, 'daily' => $dailyTransaction]);
    }

    public function airtime()
    {
       
    }


}
