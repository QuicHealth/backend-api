<?php

namespace App\Http\Controllers\Patient;

use App\User;
use App\Hospital;
use App\Doctor;
use Illuminate\Http\Request;
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

        return $this->sendResponse($patient->toArray(), 'Patient retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function edit(Patient $patient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Patient $patient)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'fullname' => 'required',
            'email' => 'required',
            'phone_number' => 'required'
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $patient->name = $input['name'];
        $patient->detail = $input['detail'];
        $patient->save();

        return $this->sendResponse($patient->toArray(), 'updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Patient  $patient
     * @return \Illuminate\Http\Response
     */
    public function destroy(Patient $patient)
    {
        $patient->delete();

        return $this->sendResponse($patient->toArray(), 'Deleted successfully.');
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
