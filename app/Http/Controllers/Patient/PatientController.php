<?php

namespace App\Http\Controllers\Patient;

use App\User;
use App\Hospital;
use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;

class PatientController extends Controller
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

    public function getHospitals(){
        $hospitals = Hospital::all();
        return response([
            'status' => true,
            'hospitals' => $hospitals
        ]);
    }
    public function getHospital($id){
        $hospital = Hospital::where('unique_id', $id)->first();
        if(!$hospital){
            return response([
                'status' => false,
                'msg' => 'Hospital not found'
            ],404);
        }
        return response([
            'status' => true,
            'hospital' => $hospital
        ]);
    }
    public function getRandomHospitals(){
        $hospitals = Hospital::inRandomOrder()->limit(4)->get();;
        return response([
            'status' => true,
            'hospitals' => $hospitals
        ]);
    }

    public function getRandomDoctors(){
        $doctors = Doctor::inRandomOrder()->limit(4)->get();;
        return response([
            'status' => true,
            'doctors' => $doctors
        ]);
    }

    public function getDoctors(){
        $doctors = Doctor::where('status', 1)->paginate(12);
        return response([
            'status' => true,
            'doctors' => $doctors
        ]);
    }

    public function getDoctor($id){
        $doctor = Doctor::where('unique_id', $id)->first();
        if(!$doctor){
            return response([
                'status' => false,
                'msg' => 'Doctor not found'
            ],404);
        }
        return response([
            'status' => true,
            'doctor' => $doctor
        ]);
    }
}
