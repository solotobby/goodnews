<?php

namespace App\Http\Controllers;

use App\Models\SmeData;
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
}
