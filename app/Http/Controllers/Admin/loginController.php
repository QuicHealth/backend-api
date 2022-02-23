<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class loginController extends Controller
{
    public function getLogin(){
        return view('admin.login');
    }

    public function login(Request $request){
        $this->validate($request, [
            'username'=>'required|string',
            'password'=>'required|string'
        ]);

        if(Auth::guard('admin')->attempt(['username'=>$request->username, 'password'=>$request->password])){
            return redirect()->intended('admin');
        }
        return back();
    }
}
