<?php

namespace App\Http\Controllers\Patient;

use App\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AppointmentController extends Controller
{
    public function createAppointment(Request $request)
    {
        $this->validate($request, [
            'doctor_id' => 'required',
            'day_id' => 'required',
            'to' => 'required',
            'from' => 'required',
        ]);

        $appointment = new Appointment();

        $appointment->user_id = Auth::user($request->token)->id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->day_id = $request->day_id;
        $appointment->from = $request->from;
        $appointment->to = $request->to;
        $appointment->status = 0;
        $appointment->unique_id = Str::random(16);

        if ($appointment->save()) {
            return response([
                'status' => true,
                'message' => 'Success! Appointment created',
                'Appointments' => $appointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Error rescheduling, pls try again',
            ], http_response_code());
        }
    }


    public function getAll(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::user($request->token)->id)->get();

        if ($appointments) {
            return response([
                'status' => true,
                'Appointments' => $appointments,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Can not find Appointments, pls try again',
            ], http_response_code());
        }
    }

    public function findAppointment(Request $request, $id)
    {

        $findAppointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        if ($findAppointment) {
            return response([
                'status' => true,
                'Appointments' => $findAppointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Can the find Appointment, pls try again',
            ], http_response_code());
        }
    }

    public function rescheduleAppointment(Request $request, $id)
    {
        $this->validate($request, [
            'from' => 'required',
            'to' => 'required',
        ]);

        $appointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        $appointment->user_id = $request->user_id;
        $appointment->doctor_id = $request->doctor_id;
        $appointment->day_id = $request->day_id;
        $appointment->from = $request->from;
        $appointment->to = $request->to;
        $appointment->status = 0;

        if ($appointment->save()) {
            return response([
                'status' => true,
                'Appointments' => $appointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Error rescheduling, pls try again',
            ], http_response_code());
        }
    }

    public function cancelAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        $appointment->status = 2;

        if ($appointment->save()) {
            return response([
                'status' => true,
                'Appointments' => $appointment,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Error Cancelling Appointment, pls try again',
            ], http_response_code());
        }
    }

    public function completedAppointment(Request $request, $id)
    {
        # code...
    }

    public function viewAppointmentReport(Appointment $appointment)
    {
        # code...
    }
}