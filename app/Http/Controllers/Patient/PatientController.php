<?php

namespace App\Http\Controllers\Patient;

use App\Models\User;
use App\Models\Doctor;
use App\Models\Report;
use App\Models\Hospital;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Services\SettingService;
use App\Http\Controllers\Controller;
use App\Http\Requests\SettingsRequest;
use App\Http\Resources\DoctorResource;
use App\Services\HealthProfileService;
use App\Http\Controllers\helpController;
use App\Http\Resources\HospitalResource;
use App\Http\Requests\HealthProfileRequest;
use App\Http\Requests\UpdatePatientRequest;
use Symfony\Component\HttpFoundation\Response as RES;

class PatientController extends Controller
{
    protected $service;

    public function __construct()
    {
        if (auth()->check()) {
            $this->service = new SettingService(new User, auth()->user()->id);
        }
    }

    public function updateProfile(UpdatePatientRequest $request, $unique_id)
    {

        // Retrieve the validated input data...
        // $validated = $request->validated();
        $this->validate($request, [
            'firstname'     => 'sometimes',
            'lastname'      => 'sometimes',
            'gender'        => 'sometimes',
            'dob'           => 'sometimes',
            'phone'         => 'sometimes'
        ]);

        $user = User::where('unique_id', $unique_id)->first();
        // $request->has('email') ? $user->email = $request->email : false;
        $request->has('firstname') ? $user->firstname = $request->firstname : false;
        $request->has('lastname') ? $user->lastname = $request->lastname : false;
        $request->has('gender') ? $user->gender = $request->gender : false;
        $request->has('dob') ? $user->dob = $request->dob : false;
        $request->has('phone') ? $user->phone = $request->phone : false;

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

        // $status = true;
        // $message = 'Login Successful';
        // $code = RES::HTTP_OK;
        // $data = [
        //     'id' => $user->id,
        //     'name' => $user->firstname,
        //     'name' => $user->lastname,
        //     'email' => $user->email,
        //     'phone_number' => $user->phone,
        // ];
        // return helpController::getResponse($status, $message, $code, $data);
    }

    public function getDashboard()
    {

        $user = auth()->user();

        $totalPendingAppointments  = Appointment::where('user_id', auth()->user()->id)->where('status', 'pending')->count();
        $totalBookedAppointments = Appointment::where('user_id', auth()->user()->id)->count();
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Token expired, pls login again',
            ], 401);
        }
        return response([
            'status' => true,
            'data' => [
                'user' => $user,
                'totalBookedAppointments' => $totalBookedAppointments,
                'totalPendingAppointments' => $totalPendingAppointments,
            ],
        ], 200);
    }

    public function getHospitals()
    {
        $hospitals = Hospital::with(['doctors', 'settings'])->latest()->get();

        if ($hospitals) {
            return response([
                'status' => true,
                // 'hospital' =>  HospitalResource::collection($hospitals),
                'hospital' => $hospitals,
            ], RES::HTTP_OK);
        }

        $status = false;
        $message = 'No hospital has added yet';
        $code = RES::HTTP_NO_CONTENT;
        return helpController::getResponse($status, $message, $code);
    }

    public function getHospital($id)
    {
        $hospital = Hospital::where('unique_id', $id)->with(['doctors', 'settings'])->first();
        if (!$hospital) {
            return response([
                'status' => false,
                'message' => 'Hospital not found',
            ], 404);
        }

        return response([
            'status' => true,
            'hospital' => $hospital,
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

    public function getHealthProfile()
    {
        $health  = new HealthProfileService();

        return $health->profile()->get();
    }

    public function updateHealthProfile(HealthProfileRequest $request)
    {
        // get validated
        $validated = $request->validated();

        // get instance of health profile service
        $health  = new HealthProfileService();
        dd($validated);
        return $health->profile()->update($validated);
    }

    public function history()
    {
        $healthRecord = Report::where('user_id', auth()->user()->id)->with('appointments')->get();

        if ($healthRecord) {
            return response()->json([
                'status' => 'success',
                'data' => $healthRecord
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'No health record found'
            ]);
        }
    }

    public function getsetting(): array
    {
        return  $this->service->settings()->get();
    }

    public function updateSetting(SettingsRequest $request)
    {

        $validated = $request->validated();

        if ($request->hasfile('image')) {
            $validated['image']  = $request->file('image')->getRealPath();
        }

        return $this->service->settings()->saveUpdate($validated, "doctor");
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password' => 'required|confirmed',
        ]);

        return $this->service->settings()->saveUpdatePassword($request->all());
    }


    //5MC4j689zQriZVsyar8otChFyoQ74smLnIcjwPJJq9qyGR9syv7MFwy3OuBN

    // NlyErEeDRNGsmnUDsgMJ2DAOA7TucjilDHSdn6rlCgp8eNDFD2lFFHCceo9p
}