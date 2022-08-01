<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ServiceController extends Controller
{
    public function index()
    {
        //return 'index';

        return $res = Http::withHeaders([
            'Accept' => 'application/json',
            // 'Content-Type' => 'application/json-patch+json',
            'Authorization' => 'Token 08f3904ff3ab0d458b7d7c6275d1116fcc0b5502'
         ])->get('https://www.geodnatech.com/api/data/1494083')->throw();

        // $payload = [
        //     "network" => 1,
        //     "mobile_number"=> "08137331282",
        //     "plan"=> plan_id,
        //     "Ported_number"=>true
        // ];

        // return $res = Http::withHeaders([
        //     'Accept' => 'application/json',
        //     'Content-Type' => 'application/json',
        //     'Authorization' => 'Token 08f3904ff3ab0d458b7d7c6275d1116fcc0b5502'
        // ])->post('https://www.geodnatech.com/api/data/', $payload)->throw();



    }
}
