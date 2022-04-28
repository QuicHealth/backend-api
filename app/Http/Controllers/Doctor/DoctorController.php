<?php

namespace App\Http\Controllers\Doctor;

use App\Doctor;
use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DoctorController extends Controller
{
    public function getDays()
    {
        $days = DB::table('days')->get();
        return response([
            'status' => true,
            'data' => $days
        ]);
    }

    public function setSchedule(Request $request)
    {
        $this->validate($request, [
            'day' => 'required|integer',
            'from' => 'required|string',
            'to' => 'required|string',
        ]);
        $app = new Appointment();
        $app->day_id = $request['day'];
        $app->from = $request['from'];
        $app->to = $request['to'];
        $app->doctor_id = 1;

        $app->save();
        return response([
            'status' => true,
            'msg' => 'Schedule saved successfully'
        ]);
    }

    public function getSchedule()
    {
        $app = Appointment::where('doctor_id', 1)->get();
        return response([
            'status' => true,
            'data' => $app
        ]);
    }

    public function getDoctorsDashboard(Request $request)
    {
        # code...
    }

    public function testDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required',
            'address' => 'required|string',
            'specialty' => 'required|integer',
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
        $hos->hospital_id = $request->hospital_id;

        if (!$hos->save()) {
            return response([
                'status' => true,
                'msg' => 'Error saving doctor'
            ]);
        }

        return response([
            'status' => true,
            'msg' => 'Doctor saved successfully',
            'data' => $hos
        ]);
    }
}