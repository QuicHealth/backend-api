<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\HelpController;
use Illuminate\Support\Facades\Session;


class AdminAuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        return view('admin.login');
    }

    public function authentiate(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $credentials = [
            'email' => $request->email,
            'password' => $request->password
        ];

        if (Auth::guard('admin')->attempt($credentials)) {

            $request->session()->regenerate();
            return redirect()->intended('admin');
        } else {
            HelpController::flashSession(false, "Invalid login details");
            return back();
        }
    }


    public function logout()
    {
        auth()->guard('admin')->logout();
        Session::flush();
        return redirect()->route('admin.login');
    }
}
