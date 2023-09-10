<?php

namespace App\Http\Controllers\Hospital;

use App\Models\Doctor;
use App\Models\Hospital;
use App\Jobs\MailSendingJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Support\Facades\Auth;

class HospitalController extends Controller
{

    public function getDashboard(Request $request)
    {
        $hospital = Auth('hospital_api')->user();
        $hospital = Hospital::where('unique_id', Auth('hospital_api')->user()->unique_id)->with('settings')->paginate(12);
        return response([
            'status' => true,
            'doctors' => $hospital,
        ]);

        $hospital = Auth('hospital_api')->user();
        if (!$hospital) {
            return response()->json([
                'status' => false,
                'msg' => 'Token expired, pls login again',
            ]);
        } else {
            // $gethospital = Hospital::where( 'hospital_id', $hospital->id )->with( 'doctors' )->paginate( 12 );
            return response([
                'status' => true,
                'data' => array_merge(collect($hospital)->toArray()),
            ], 200);
        }
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|unique:hospitals',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'description' => 'required|string',
            'phone' => 'required|string',
        ]);

        $hospital = Hospital::where('id', Auth::user($request->token)->id)->first();

        $hospital->name = $request->name;
        $hospital->email = $request->email;
        $hospital->image = $request->image;
        $hospital->city = $request->city;
        $hospital->state = $request->state;
        $hospital->country = $request->country;
        $hospital->description = $request->description;
        $hospital->phone = $request->phone;

        $imageName = time() . '.' . $request->image->extension();

        $request->image->move(public_path('images/hospitals'), $imageName);

        if (!$hospital->save()) {
            return response([
                'status' => false,
                'msg' => 'Error updating profile, pls try again',
            ]);
        }

        return response([
            'status' => true,
            'msg' => 'Profile updated successfully',
            'data' => $hospital,
        ]);
    }

    public function getSpecialties()
    {
        $spec = DB::table('specialties')->get();
        return response([
            'status' => true,
            'data' => $spec
        ]);
    }

    public function testHospital(Request $request)
    {

        $this->validate($request, [
            'name' => 'required|string|unique:hospitals',
            'email' => 'required|email|unique:hospitals',
            'phone' => 'required',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'password' => 'required|min:6',
        ]);

        $hos = new Hospital();
        $hos->name = $request->name;
        $hos->email = $request->email;
        $hos->phone = $request->phone;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->city = $request->city;
        $hos->state = $request->state;
        $hos->password = bcrypt($request->password);;
        $hos->country = $request->country;

        if ($request['longitude']) {
            $hos->longitude = $request['longitude'];
        }
        if ($request['latitude']) {
            $hos->latitude = $request['latitude'];
        }

        if (!$hos->save()) {
            return response([
                'status' => false,
                'msg' => 'Error saving Hospital'
            ]);
        }

        return response([
            'status' => true,
            'msg' => 'Hospital saved successfully',
            'data' => $hos
        ]);
    }


    public function addDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required',
            'address' => 'required|string',
            'specialty' => 'required|string',
        ]);

        $password = rand(111111, 999999);


        $hos = new Doctor();
        $hos->name = $request->name;
        $hos->phone = $request->phone;
        $hos->password = bcrypt($password);
        $hos->email = $request->email;
        $hos->unique_id = uniqid();
        $hos->address = $request->address;
        $hos->specialty = $request->specialty;
        $hos->hospital_id = Auth('hospital')->user()->id;

        if (!$hos->save()) {
            return response([
                'status' => true,
                'msg' => 'Error saving doctor'
            ]);
        }
        $data = [
            'email' => $request->email,
            'subject' => 'Welcome Mail',
            'name' => 'Welcome',
            'view' => 'mail.mail',
            'content' =>
            'Welcome to Quichealth, we are happy to have you here. Your doctor profile has been created, below are profile details <br>
                Name: ' . $request->name .
                '<br> Email: ' . $request->email .
                '<br> Hospital: ' . Auth('hospital')->user()->name .
                '<br> Password: ' . $password .
                '<br> Phone: ' . $request->phone .
                '<br> Address: ' . $request->address
        ];
        MailSendingJob::dispatch($data);

        return response([
            'status' => true,
            'msg' => 'Doctor saved successfully'
        ]);
    }
}