<?php

namespace App\Http\Controllers;

use App\Models\SmeData;
use App\Models\User;
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
}
