<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Timeslot;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ScheduleRequest;

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

    public function setSchedule(ScheduleRequest $request)
    {
        $validated = $request->validated();

        $setSchedule = Schedule::updateOrCreate(
            ['doctor_unique_id' => $validated['doctor_unique_id'], 'day_id' => $validated['day_id']],
            ['date' =>  Carbon::now()->toFormattedDateString()]
        );

        if ($setSchedule) {
            for ($i = 0; $i < count($validated['time_slots']); $i++) {
                Timeslot::updateOrCreate(
                    ['schedule_id' => $setSchedule->id, 'start' => $validated['time_slots'][$i]['start'], 'end' => $validated['time_slots'][$i]['end']],
                    ['selected' =>  $validated['time_slots'][$i]['selected'], 'status' =>  $validated['time_slots'][$i]['status']]
                );
            }

            return response([
                'status' => true,
                'message' => 'Schedule saved successfully'
            ]);
        }

        return response([
            'status' => false,
            'message' => "Can not set schedules",
        ], 402);
    }

    public function getSchedule()
    {
        $schedule = Schedule::where('doctor_unique_id', 1)->with('timeslot')->get();
        return response([
            'status' => true,
            'data' => $schedule
        ]);
    }

    public function getavailble()
    {
        # code...
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