<?php 

namespace App\Http\Controllers\Traits;

use Illuminate\Support\Facades\Http;

trait Capitalsage{

    public function authorize()
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

}