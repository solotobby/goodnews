<?php

namespace App\Http\Controllers;

use App\Models\BankInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WithdrawalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function withdraw(Request $request)
    {
        $getBankInfo = BankInformation::where('user_id', auth()->user()->id)->first();
        $payload = [
            "account_bank"=> $getBankInfo->bank_code,
            "account_number"=> $getBankInfo->account_number,
            "amount" => $request->amount,
            "country" => "NG",
            "narration"=> "Akhlm Pstmn Trnsfr xx007",
            "reference" => time() //\Str::random(10)
        ];

       $res = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.env('FL_SECRET_KEY')
        ])->post('https://api.flutterwave.com/v3/transfers', $payload)->throw();


    }
}
