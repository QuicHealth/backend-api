<?php

namespace App\Http\Controllers\Doctor;

use App\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Schedule;

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
            'day_id' => 'required|integer', // monday
            'from' => 'required|date_format:H:i|unique:schedules', // 8.00am
            'to' => 'required|date_format:H:i|after:from|unique:schedules', // 4.00pm
        ]);

        $hourdiff = (strtotime($request->to) - strtotime($request->from)) / 3600;

        // return $hourdiff;

        if ($hourdiff == 1) {
            $schedule = new Schedule();
            $schedule->doctor_id = $request->doctor_id;
            $schedule->day_id = $request->day_id;
            $schedule->from = $request->from;
            $schedule->to = $request->to;

            $schedule->save();

            return response([
                'status' => true,
                'msg' => 'Schedule saved successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'Time must be one hour',
        ], 402);
    }

    public function getSchedule()
    {
        $schedule = Schedule::where('doctor_id', 1)->get();
        return response([
            'status' => true,
            'data' => $schedule
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

        // $password = rand(111111, 999999);


        $hos = new Doctor();
        $hos->name = $request->name;
        $hos->phone = $request->phone;
        $hos->password = bcrypt($request->password);
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
