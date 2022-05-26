<?php

namespace App\Http\Controllers;

use App\Models\BankInformation;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            $users = User::role('user')->get();
            $flw_Balance = $this->checkBalance();
            return view('admin.home', ['wallet' => $adminWallet, 'transactions' => $transactions, 'users'=>$users, 'extBalance' => $flw_Balance]); 
        }
        $wallet = Wallet::where('user_id', auth()->user()->id)->first();
        $transaction = Transaction::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')->get();

        $monthlyTransaction = Transaction::whereMonth('created_at', Carbon::now()->month)->get();
        $dailyTransaction = Transaction::whereDate('created_at', Carbon::today())->get();
        $getBankInfo = BankInformation::where('user_id', auth()->user()->id)->first();
        $bankList = [];
        if($getBankInfo == null){
            $bankList = $this->bankList();
        }
       
        //$topup = $monthlyTransaction->where('')
        return view('user.home', [
            'wallet' => $wallet, 
            'transactions' => $transaction, 
            'monthly' => $monthlyTransaction, 
            'daily' => $dailyTransaction,
            'bankList' => $bankList,
            'bankInformation' => $getBankInfo
        ]);
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

    public function bankList()
    {
        return $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->get('https://api.flutterwave.com/v3/banks/NG')->throw()['data'];
    }

    public function resolveBank($bank_code, $accoun_num){
        $payload = [
            "account_number"=> "0690000032",
            "account_bank"=> "044"
        ];

        return Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
         ])->post('https://api.flutterwave.com/v3/accounts/resolve', $payload)->throw();
    }

    public function storeBankInformation(Request $request){
    
        BankInformation::create([
            'user_id' => auth()->user()->id,
            'bank_code' => $request->bank_code,
            'account_number' => $request->account_number
        ]);
        return back()->with('success', 'Bank account created successfully');

    }

    


}
