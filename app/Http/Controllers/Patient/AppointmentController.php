<?php

namespace App\Http\Controllers\Patient;

use App\Models\Appointment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Actions\CreateAppointmentAction;
use App\Actions\AppointmentDetailsAction;
use App\Http\Requests\CreateAppointmentRequest;


class AppointmentController extends Controller
{
    public function createAppointment(CreateAppointmentRequest $request)
    {
        $validated = $request->validated();

        $user_id = Auth::user($request->token)->id;
        $date = Carbon::now()->toFormattedDateString();

        Appointment::truncate();

        return CreateAppointmentAction::run($validated, $user_id, $date);
    }


    public function getAll(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::user($request->token)->id)
            ->with('doctor')
            ->get();

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
            ->with('doctor')
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
            "time_slots.start"  => "required|date_format:H:i",
            "time_slots.end"  => "required|date_format:H:i",
        ]);


        // ->first()

        $checkAppointmentBooking = Appointment::where('doctor_unique_id', $request->doctor_unique_id)
            ->where('day_id', $request->day_id)
            ->where('start', $request->time_slots['start'])
            ->where('end', $request->time_slots['end'])
            ->first();

        if ($checkAppointmentBooking) {

            return response([
                'status' => false,
                'message' => 'Time slot have already been booked',
            ], 403);
        }

        $appointment = Appointment::where('unique_id', $id)
            ->where('user_id', Auth::user($request->token)->id)
            ->update(['start' => $request->time_slots['start'], 'end' => $request->time_slots['end']]);


        // $appointment->user_id = Auth::user($request->token)->id;
        // $appointment->doctor_unique_id = $request->doctor_unique_id;
        // $appointment->day_id = $request->day_id;
        // $appointment->time_slots = json_encode($request->time_slots);
        // $appointment->date = Carbon::now()->toFormattedDateString();
        // $appointment->status = 'successfull';
        // $appointment->payment_status = 'pending';
        // $appointment->payment_reference = '';
        // $appointment->unique_id = Str::random(16);

        if ($appointment) {
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

    public function appointmentDetails(Request $request, $id)
    {
        # code...
    }
    public function completedAppointment(Request $request, $id)
    {
        # code...
    }

    public function viewAppointmentReport(Appointment $appointment)
    {
        # code...
    }

    function getTimeSlot($interval, $start_time, $end_time)
    {
        // $start = new DateTime($start_time);
        // $end = new DateTime($end_time);
        // $startTime = $start->format('H:i');
        // $endTime = $end->format('H:i');
        // $i = 0;
        // $time = [];
        // while (strtotime($startTime) <= strtotime($endTime)) {
        //     $start = $startTime;
        //     $end = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
        //     $startTime = date('H:i', strtotime('+' . $interval . ' minutes', strtotime($startTime)));
        //     $i++;
        //     if (strtotime($startTime) <= strtotime($endTime)) {
        //         $time[$i]['slot_start_time'] = $start;
        //         $time[$i]['slot_end_time'] = $end;
        //     }
        // }
        // return $time;
    }

    //     $slots = getTimeSlot(30, '10:00', '13:00');
    // echo '<pre>';
    // print_r($slots);
}
