<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use CodeZero\UniqueTranslation\UniqueTranslationRule;

class AppointmentController extends Controller
{
    public function createAppointment(Request $request)
    {
        $this->validate($request, [
            'doctor_unique_id' => 'required',
            'day_id' => 'required',
            "time_slots.start"  => "required",
            "time_slots.*"  => "required|date_format:H:i",
        ]);

        // $hourdiff = (strtotime($request->to) - strtotime($request->from)) / 3600;

        // return $hourdiff;

        // if ($hourdiff == 1) {
        $appointment = new Appointment();

        $checkAppointmentBooking = $appointment->where('doctor_unique_id', $request->doctor_unique_id)
            ->where('day_id', $request->day_id)
            ->whereJsonContains('time_slots->start', $request->time_slots['start'])
            ->whereJsonContains('time_slots->end', $request->time_slots['end'])
            ->first();

        if ($checkAppointmentBooking) {
            return response([
                'status' => false,
                'message' => 'Time slot have already been booked',
            ], 403);
        }

        $appointment->user_id = Auth::user($request->token)->id;
        $appointment->doctor_unique_id = $request->doctor_unique_id;
        $appointment->day_id = $request->day_id;
        $appointment->time_slots = json_encode($request->time_slots);
        $appointment->date = Carbon::now()->toFormattedDateString();
        $appointment->status = 'successfull';
        $appointment->payment_status = 'pending';
        $appointment->payment_reference = '';
        $appointment->unique_id = Str::random(16);

        // $appointment = Appointment::create([
        //     "user_id" => Auth::user($request->token)->id,
        //     "doctor_id" => $request->doctor_id,
        //     "day_id" => $request->day_id,
        //     "time_slots" => json_encode($request->time_slots),
        //     "date" => Carbon::now()->diffForHumans(),
        //     "status" => 'successfull',
        //     "payment_status" => 'pending',
        //     "payment_reference" => '',
        //     "unique_id" => Str::random(16),
        // ]);

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
        // }

        // return response([
        //     'status' => false,
        //     'message' => 'Time must be one hour',
        // ], 406);
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
            'doctor_unique_id' => 'required',
            'day_id' => 'required',
            "time_slots.start"  => "required",
            "time_slots.*"  => "required|date_format:H:i",
        ]);

        $appointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->first();

        $checkAppointmentBooking = $appointment->where('doctor_unique_id', $request->doctor_unique_id)
            ->where('day_id', $request->day_id)
            ->whereJsonContains('time_slots->start', $request->time_slots['start'])
            ->whereJsonContains('time_slots->end', $request->time_slots['end'])
            ->first();

        if ($checkAppointmentBooking) {
            return response([
                'status' => false,
                'message' => 'Time slot have already been booked',
            ], 403);
        }

        $appointment->user_id = Auth::user($request->token)->id;
        $appointment->doctor_unique_id = $request->doctor_unique_id;
        $appointment->day_id = $request->day_id;
        $appointment->time_slots = json_encode($request->time_slots);
        $appointment->date = Carbon::now()->toFormattedDateString();
        $appointment->status = 'successfull';
        $appointment->payment_status = 'pending';
        $appointment->payment_reference = '';
        $appointment->unique_id = Str::random(16);

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

        $appointment->status = "cancelled";

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