<?php

namespace App\Http\Controllers\Doctor;

use App\Models\Zoom;
use App\Models\Doctor;
use App\Models\Schedule;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\SetAvailablityAction;
use App\Http\Requests\ScheduleRequest;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\ScheduleResource;

class DoctorController extends Controller
{
    // public function getDays()
    // {
    //     $days = DB::table('days')->get();
    //     return response([
    //         'status' => true,
    //         'data' => $days
    //     ]);
    // }

    public function setSchedule(ScheduleRequest $request)
    {
        $validated = $request->validated();

        return SetAvailablityAction::run($validated);
    }

    public function getSchedule()
    {
        $schedule = Schedule::where('doctor_id', auth('doctor_api')->user()->id)->with('timeslot')->get();
        return response([
            'status' => true,
            'data' => $schedule
        ]);
    }

    public function searchSchedule($date)
    {
        $schedule = Schedule::where('doctor_id', auth('doctor_api')->user()->id)
            ->where('date', $date)
            ->with('timeslot')
            ->get();

        return response([
            'status' => true,
            'scheduling' =>  ScheduleResource::collection($schedule)
        ]);
    }


    public function getDoctorsDashboard(Request $request)
    {
        $doctor = Doctor::where("id", auth('doctor_api')->user()->id)
            ->with(['hospital', 'schedule', 'appointments'])
            ->first();

        // dd($doctor);DoctorResource::collection

        return response()->json(['success' => true, 'doctor' => new DoctorResource($doctor)]);
    }

    public function testDoctor(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:doctors',
            'phone' => 'required',
            'address' => 'required|string',
            'specialty' => 'required|integer',
            'password' => 'required|min:6',
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

    public function getMeetingsByDoctor()
    {
        $meetings = Zoom::where('doctor_id', auth()->user()->id)->get();

        if ($meetings) {
            return response()->json([
                'status' => true,
                'message' => 'success',
                'data' => $meetings
            ], 200);
        }

        return response()->json([
            'status' => false,
            'message' => 'error',
            'data' => []
        ], 422);
    }

    public function appointmentByPaymentStatus(Request $request)
    {
        $appointments = Appointment::where('doctor_id', Auth::user($request->token)->id)
            ->where('payment_status', 'PAID')
            ->with(['user', 'zoomMeeting'])
            ->get();

        if ($appointments) {
            return response([
                'status' => true,
                'Appointments' => $appointments,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'You dont have any appointments',
            ], http_response_code());
        }
    }
}