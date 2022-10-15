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
use App\Events\NotificationReceived;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\AppointmentDetailsRequest;
use App\Models\Doctor;
use App\Models\Notification;
use App\Models\User;
use App\Notifications\CancelAppointmentNotification;

class AppointmentController extends Controller
{
    public function createAppointment(CreateAppointmentRequest $request)
    {
        $validated = $request->validated();

        $user_id = Auth::user($request->token)->id;
        // $date = Carbon::now()->toFormattedDateString();

        // Appointment::truncate();

        return CreateAppointmentAction::run($validated, $user_id);
    }

    public function appointmentDetails(AppointmentDetailsRequest $request)
    {
        $validated = $request->validated();

        $data = [
            'appointment_id' =>  $validated['appointment_id'],
            'purpose' =>  $validated['purpose'],
            'length' =>  $validated['length'],
            'treatments' =>  $validated['treatments'],
            'others' =>  $validated['others']
        ];

        $details = AppointmentDetailsAction::run($data);

        if ($details) {
            return response([
                'status' => true,
                'message' => 'Success! Proceed to payment',
                'Appointments' => $details,
            ], http_response_code());
        } else {
            return response([
                'status' => false,
                'message' => 'Sorry! this Appointment has not been created',
            ], http_response_code());
        }
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

    public function appointmentByPaymentStatus(Request $request)
    {
        $appointments = Appointment::where('user_id', Auth::user($request->token)->id)
            ->where('payment_status', 'PAID')
            ->with(['doctor', 'zoomMeeting'])
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

            //save db notification
            $user_id = auth()->user()->id;
            $doc_id = $appointment->doctor_id;
            $title = 'Appointment Reschedule';
            $message = 'Appointment was Reschedule Successful';
            $usertype = 'Patient';
            $this->sendNotification($user_id, $doc_id, $usertype, $title, $message);

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

            $user_id = $appointment->user_id;
            $user = User::find($user_id);
            $appointment->user()->associate($user);

            // $appointment->notify(new CancelAppointmentNotification($appointment));

            //save db notification
            $doc_id = $appointment->doctor_id;
            $title = 'Appointment Cancelled';
            $message = 'Appointment was cancelled';
            $usertype = 'Patient';
            $this->sendNotification($user_id, $doc_id, $usertype, $title, $message);
        }
    }

    public function sendNotification($authUserId, $receiverId, $usertype, $title, $message)
    {
        //save user notification
        $notification = new Notification();
        $notification->user_id = $authUserId;
        $notification->receiverId = $receiverId;
        $notification->user_type = $usertype;
        $notification->title = $title;
        $notification->message = $message;
        if ($notification->save()) {
            return true;
        }
        return false;
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
