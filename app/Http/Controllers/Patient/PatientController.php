<?php

namespace App\Http\Controllers\Patient;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class PatientController extends BaseController
{
    public function updateProfile(Request $request){
        $this->validate($request, [
            'firstname'=>'required|string',
            'lastname'=>'required|string',
            'email'=>'required',
            'gender'=>'required|string',
            'phone'=>'required|string',
            'dob'=>'required|string',
        ]);
        $user = User::where('id', Auth::user($request->token)->id)->first();
//        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;

        if (!$user->save()) {
            return response([
                'status' => false,
                'msg' => 'Error updating profile, pls try again',
            ]);
        }

        return response([
            'status' => true,
            'msg' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    public function getDashboard(Request $request){
        $user=Auth::user();
        if(!$user){
            return response()->json([
                'status' => false,
                'msg' => 'Token expired, pls login again'
            ]);
        }
        return response([
            'status' => true,
            'data' => array_merge(collect($user)->toArray()),
        ], 200);
    }
}
