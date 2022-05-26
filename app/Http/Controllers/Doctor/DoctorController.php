<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
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
            "time_slots"    => "required",
            "time_slots.*.*"  => "required|date_format:H:i|distinct",
        ]);

        $schedule = new Schedule();
        $schedule->doctor_id = $request->doctor_id;
        $schedule->day_id = $request->day_id;
        $schedule->time_slots = json_encode($request->time_slots, JSON_PRETTY_PRINT);
        $schedule->date = Carbon::now();

        $setSchedule = Schedule::updateOrCreate(
            ['doctor_id' => $request->doctor_id, 'day_id' => $request->day_id],
            ['time_slots' => json_encode($request->time_slots), 'date' =>  Carbon::now()]
        );

        if ($setSchedule) {
            return response([
                'status' => true,
                'msg' => 'Schedule saved successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => 'error',
        ], 402);
    }

    public function getSchedule()
    {
        $schedule = Schedule::where('doctor_id', 1)->get();
        return response([
            'status' => true,
            'data' => $schedule,
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