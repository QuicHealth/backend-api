<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\helpController;
use Symfony\Component\HttpFoundation\Response as RES;

class PatientController extends Controller
{
    public function updateProfile(Request $request, $unique_id)
    {
        $this->validate($request, [
            'firstname' => 'required|string',
            'lastname' => 'required|string',
            'email' => 'required',
            'gender' => 'required|string',
            'phone' => 'required|string',
            'dob' => 'required|string',
        ]);
        $user = User::where('unique_id', $unique_id)->first();
        //        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;

        if (!$user->save()) {
            return response([
                'status' => false,
                'message' => 'Error updating profile, pls try again',
            ], 400);
        }

        return response([
            'status' => true,
            'message' => 'Profile updated successfully',
            'data' => $user,
        ]);

        $status = true;
        $message = 'Login Successful';
        $code = RES::HTTP_OK;
        $data = [
            'id' => $user->id,
            'name' => $user->firstname,
            'name' => $user->lastname,
            'email' => $user->email,
            'phone_number' => $user->phone,
        ];
        return helpController::getResponse($status, $message, $code, $data);
    }

    public function getDashboard(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Token expired, pls login again',
            ], 401);
        }
        return response([
            'status' => true,
            'data' => array_merge(collect($user)->toArray()),
        ], 200);
    }

    public function getHospitals()
    {
        $hospitals = Hospital::get()->toArray();
        if (!$hospitals || !count($hospitals)) {
            $status = false;
            $message = 'No hospital has added yet';
            $code = RES::HTTP_OK;
            return helpController::getResponse($status, $message, $code);
        }

        $status = true;
        $message = 'List of all the hospitals';
        $code = RES::HTTP_NO_CONTENT;
        $data = $hospitals;
        return helpController::getResponse($status, $message, $code, $data);
    }
    public function getHospital($id)
    {
        $hospital = Hospital::where('unique_id', $id)->first()->toArray();
        if (!$hospital) {
            return response([
                'status' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        $status = true;
        $message = 'Hospital found';
        $code = RES::HTTP_OK;
        $data = $hospital;

        return helpController::getResponse($status, $message, $code, $data);
    }
    public function getRandomHospitals()
    {
        $hospitals = Hospital::inRandomOrder()->limit(4)->get();
        return response([
            'status' => true,
            'hospitals' => $hospitals,
        ]);
    }

    public function getRandomDoctors()
    {
        $doctors = Doctor::inRandomOrder()->limit(4)->get();
        return response([
            'status' => true,
            'doctors' => $doctors,
        ]);
    }

    public function getDoctors()
    {
        $doctors = Doctor::where('status', 1)->paginate(12);
        return response([
            'status' => true,
            'doctors' => $doctors,
        ]);
    }

    public function getDoctor($id)
    {
        $doctor = Doctor::where('unique_id', $id)->first();
        if (!$doctor) {
            return response([
                'status' => false,
                'message' => 'Doctor not found',
            ], 404);
        }
        return response([
            'status' => true,
            'doctor' => $doctor,
        ]);
    }



    //5MC4j689zQriZVsyar8otChFyoQ74smLnIcjwPJJq9qyGR9syv7MFwy3OuBN

    // NlyErEeDRNGsmnUDsgMJ2DAOA7TucjilDHSdn6rlCgp8eNDFD2lFFHCceo9p
}