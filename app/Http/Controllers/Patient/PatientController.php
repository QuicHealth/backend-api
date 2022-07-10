<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Hospital;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DoctorResource;
use App\Http\Controllers\helpController;
use App\Http\Resources\HospitalResource;
use App\Http\Requests\UpdatePatientRequest;
use Symfony\Component\HttpFoundation\Response as RES;

class PatientController extends Controller
{
    /**
     * Update a user's record in the database.
     *
     * @param  \App\Http\Requests\UpdatePatientRequest  $request
     * @param  \App\Models\User unique_id
     *
     * @return Illuminate\Http\Response
     */

    public function updateProfile(UpdatePatientRequest $request, $unique_id)
    {

        // Retrieve the validated input data...
        $validated = $request->validated();

        $user = User::where('unique_id', $unique_id)->first();
        //        $user->email = $request->email;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->gender = $request->gender;
        $user->dob = $request->dob;
        $user->phone = $request->phone;

        //$user->update(['avatar' => $avatar]);

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
        $hospitals = Hospital::with('doctors')->get();
        if (!$hospitals || !count($hospitals)) {
            $status = false;
            $message = 'No hospital has added yet';
            $code = RES::HTTP_NO_CONTENT;
            return helpController::getResponse($status, $message, $code);
        }

        return response([
            'status' => true,
            'hospital' =>  HospitalResource::collection($hospitals),
        ], RES::HTTP_OK);
    }
    public function getHospital($id)
    {
        $hospital = Hospital::where('unique_id', $id)->with('doctors')->first();
        if (!$hospital) {
            return response([
                'status' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        // $status = true;
        // $message = 'Hospital found';
        // $code = RES::HTTP_OK;
        // $data = new HospitalResource($hospital)->toArray();

        // return helpController::getResponse($status, $message, $code, $data);
        return response([
            'status' => true,
            'hospital' => new HospitalResource($hospital),
        ], RES::HTTP_OK);
    }
    public function getRandomHospitals()
    {
        $hospitals = Hospital::inRandomOrder()->limit(4)->with('doctors')->get();

        return response([
            'status' => true,
            'hospitals' => HospitalResource::collection($hospitals),
        ]);
    }

    public function getRandomDoctors()
    {
        $doctors = Doctor::inRandomOrder()->limit(4)->with(['schedule', 'hospital'])->get();

        return response([
            'status' => true,
            'doctors' => DoctorResource::collection($doctors),
        ], RES::HTTP_OK);
    }

    public function getDoctors()
    {
        $doctors = Doctor::where('status', 1)
            ->with(['schedule', 'hospital'])
            ->paginate(12);

        return response([
            'status' => true,
            'doctors' =>  DoctorResource::collection($doctors),
        ]);
    }

    public function getDoctor($id)
    {
        $doctor = Doctor::where('unique_id', $id)
            ->with(['schedule', 'hospital'])
            ->first();
        if (!$doctor) {
            return response([
                'status' => false,
                'message' => 'Doctor not found',
            ], 404);
        }
        return response([
            'status' => true,
            'doctor' => new DoctorResource($doctor),
        ]);
    }



    //5MC4j689zQriZVsyar8otChFyoQ74smLnIcjwPJJq9qyGR9syv7MFwy3OuBN

    // NlyErEeDRNGsmnUDsgMJ2DAOA7TucjilDHSdn6rlCgp8eNDFD2lFFHCceo9p
}