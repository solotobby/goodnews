<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Wallet;
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
            return view('admin.home'); 
        }
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        $transaction = Transaction::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')->get();
        return view('user.home', ['wallet' => $wallet, 'transactions' => $transaction]);
    }

    public function airtime()
    {
       
    }


}
