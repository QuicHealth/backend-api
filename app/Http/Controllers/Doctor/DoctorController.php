<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Appointment;

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
}
